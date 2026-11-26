<?php

namespace App\Services;

use App\Enums\Action;
use App\Enums\ApplicationStatus;
use App\Enums\EntityType;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Evaluator;
use App\Models\Notification;
use App\Models\RequiredDocument;
use App\Models\Scholarship;
use App\Models\User;
use App\Support\ScholarshipAvailability;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Every multi-step change an applicant makes goes through here, inside one
 * database transaction, together with its audit log and notification.
 * A failed business rule throws DomainException with a message the applicant can read.
 *
 * Applicant owner: submit() and cancel().
 * Evaluator owner adds recordDecision(); Admin owner adds assignEvaluator().
 */
class ApplicationService
{
    public function __construct(
        private readonly EligibilityService $eligibility,
        private readonly AuditLogger $audit,
    ) {
    }

    /** Whether the applicant may still change details and documents of this application. */
    public function canEdit(Application $application): bool
    {
        return $application->isEditable()
            && ScholarshipAvailability::isOpen($application->scholarship);
    }

    /** Eligibility of the applicant's saved details, shown before submitting. */
    public function previewEligibility(Application $application): EligibilityReport
    {
        return $this->eligibility->check($application->applicant, $application->scholarship);
    }

    /** Start a draft application for an open scholarship. */
    public function createDraft(Applicant $applicant, Scholarship $scholarship, User $actor): Application
    {
        if (!ScholarshipAvailability::isOpen($scholarship)) {
            throw new DomainException('This scholarship is not open for applications.');
        }

        if ($applicant->hasActiveApplicationFor($scholarship)) {
            throw new DomainException('You already have an active application for this scholarship.');
        }

        return DB::transaction(function () use ($applicant, $scholarship, $actor) {
            $application = Application::create([
                'applicant_id' => $applicant->id,
                'scholarship_id' => $scholarship->id,
            ]);

            $this->audit->log(
                $actor,
                Action::Created,
                EntityType::Application,
                $application->id,
                'Started an application for ' . $scholarship->name . '.'
            );

            return $application;
        });
    }

    /**
     * Save the personal, academic and financial details of the form.
     * On a submitted application the snapshot and eligibility result are taken again.
     *
     * @param  array{course?:?string,address?:?string,birth_date?:?string,gwa?:mixed,annual_family_income?:mixed,year_level?:mixed}  $data
     */
    public function updateDetails(Application $application, array $data, User $actor): void
    {
        $this->assertEditable($application);

        DB::transaction(function () use ($application, $data, $actor) {
            $applicant = $application->applicant;

            $applicant->fill([
                'course' => $data['course'] ?? $applicant->course,
                'address' => $data['address'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
            ]);

            if (isset($data['gwa'])) {
                $applicant->setGwa((float) $data['gwa']);
            }
            if (isset($data['annual_family_income'])) {
                $applicant->setAnnualFamilyIncome((float) $data['annual_family_income']);
            }
            if (isset($data['year_level'])) {
                $applicant->setYearLevel((int) $data['year_level']);
            }

            $applicant->save();

            if ($application->status === ApplicationStatus::Submitted) {
                $application->refreshSnapshot($this->eligibility->check($applicant, $application->scholarship));
            }

            $this->audit->log($actor, Action::Updated, EntityType::Application, $application->id, 'Updated application details.');
        });
    }

    /** Store (or replace) the file for one required document. Files stay on the private disk. */
    public function attachDocument(Application $application, RequiredDocument $requiredDocument, UploadedFile $file, User $actor): ApplicationDocument
    {
        $this->assertEditable($application);

        if ($requiredDocument->scholarship_id !== $application->scholarship_id) {
            throw new DomainException('That document is not part of this scholarship.');
        }

        return DB::transaction(function () use ($application, $requiredDocument, $file, $actor) {
            $existing = $application->documents()->where('required_document_id', $requiredDocument->id)->first();
            $oldPath = $existing?->file_path;

            $path = $file->store('application-documents/' . $application->id, 'local');

            $document = ApplicationDocument::updateOrCreate(
                ['application_id' => $application->id, 'required_document_id' => $requiredDocument->id],
                ['file_name' => $file->getClientOriginalName(), 'file_path' => $path, 'uploaded_at' => now()]
            );

            if ($oldPath && $oldPath !== $path) {
                Storage::disk('local')->delete($oldPath);
            }

            $this->audit->log($actor, Action::Updated, EntityType::Application, $application->id, 'Uploaded ' . $requiredDocument->name . '.');

            return $document;
        });
    }

    /** Remove an uploaded file. After submission a required document can only be replaced, not removed. */
    public function removeDocument(Application $application, ApplicationDocument $document, User $actor): void
    {
        $this->assertEditable($application);

        if ($document->application_id !== $application->id) {
            throw new DomainException('That file does not belong to this application.');
        }

        if ($application->status !== ApplicationStatus::Draft && $document->requiredDocument->is_required) {
            throw new DomainException('A required document can be replaced after submission, but not removed.');
        }

        DB::transaction(function () use ($application, $document, $actor) {
            $name = $document->requiredDocument->name;
            Storage::disk('local')->delete($document->file_path);
            $document->delete();

            $this->audit->log($actor, Action::Updated, EntityType::Application, $application->id, 'Removed ' . $name . '.');
        });
    }

    /** Draft to Submitted. Checks the rules, snapshots the applicant, saves the eligibility result. */
    public function submit(Application $application, User $actor): Application
    {
        return DB::transaction(function () use ($application, $actor) {
            $locked = Application::with(['applicant', 'scholarship.requirements'])
                ->lockForUpdate()
                ->findOrFail($application->id);

            if ($locked->status !== ApplicationStatus::Draft) {
                throw new DomainException('Only a draft application can be submitted.');
            }

            if (!ScholarshipAvailability::isOpen($locked->scholarship)) {
                throw new DomainException('This scholarship is closed, so the application can no longer be submitted.');
            }

            $applicant = $locked->applicant;

            if ($applicant->gwa === null || $applicant->annual_family_income === null || $applicant->year_level === null) {
                throw new DomainException('Fill in your GWA, annual family income and year level before submitting.');
            }

            if ($applicant->hasActiveApplicationFor($locked->scholarship, $locked)) {
                throw new DomainException('You already have another active application for this scholarship.');
            }

            $missing = $locked->missingDocuments();
            if ($missing->isNotEmpty()) {
                throw new DomainException('Upload the required documents first: ' . $missing->pluck('name')->implode(', ') . '.');
            }

            $locked->submit($this->eligibility->check($applicant, $locked->scholarship));

            $this->audit->log(
                $actor,
                Action::Submitted,
                EntityType::Application,
                $locked->id,
                'Submitted the application for ' . $locked->scholarship->name . '.'
            );

            Notification::create([
                'user_id' => $actor->id,
                'application_id' => $locked->id,
                'title' => 'Application submitted',
                'message' => 'Your application for ' . $locked->scholarship->name . ' was submitted and is waiting for review.',
            ]);

            return $locked;
        });
    }

    /** Withdraw an application. Allowed while it is Draft or Submitted (BR-11). */
    public function cancel(Application $application, User $actor): Application
    {
        return DB::transaction(function () use ($application, $actor) {
            $locked = Application::with('scholarship')->lockForUpdate()->findOrFail($application->id);

            if (!$locked->isEditable()) {
                throw new DomainException('This application can no longer be cancelled.');
            }

            $locked->cancel();

            $this->audit->log(
                $actor,
                Action::Cancelled,
                EntityType::Application,
                $locked->id,
                'Cancelled the application for ' . $locked->scholarship->name . '.'
            );

            Notification::create([
                'user_id' => $actor->id,
                'application_id' => $locked->id,
                'title' => 'Application cancelled',
                'message' => 'You cancelled your application for ' . $locked->scholarship->name . '.',
            ]);

            return $locked;
        });
    }

    /** Admin: assign or reassign an application to an evaluator. */
    public function assignEvaluator(Application $application, Evaluator $evaluator, User $admin): void
    {
        $wasAssigned = $application->assigned_evaluator_id !== null;

        $application->assignTo($evaluator);

        $this->audit->log(
            $admin,
            $wasAssigned ? Action::Reassigned : Action::Assigned,
            EntityType::Application,
            $application->id,
            'Evaluator: ' . $evaluator->user->name
        );
    }

    private function assertEditable(Application $application): void
    {
        if (!$application->isEditable()) {
            throw new DomainException('This application can no longer be changed.');
        }

        if (!ScholarshipAvailability::isOpen($application->scholarship)) {
            throw new DomainException('The scholarship is closed, so the application can no longer be changed.');
        }
    }
}
