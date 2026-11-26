<?php

namespace Tests\Feature\Applicant;

use App\Enums\ApplicationStatus;
use App\Enums\EligibilityResult;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Sidebar pages, the header search bar and the apply / submit / cancel flow for the applicant role. */
class ApplicantAreaTest extends TestCase
{
    use RefreshDatabase;

    private function makeApplicant(array $profile = []): User
    {
        $user = User::factory()->create();

        Applicant::create(array_merge([
            'user_id' => $user->id,
            'student_no' => '2024-'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
            'course' => 'BSIT',
            'gwa' => 1.75,
            'annual_family_income' => 200000,
            'year_level' => 3,
        ], $profile));

        return $user->fresh();
    }

    private function makeScholarship(array $overrides = []): Scholarship
    {
        $scholarship = Scholarship::create(array_merge([
            'name' => 'Academic Excellence Scholarship',
            'description' => 'For students with high grades.',
            'type' => 'academic',
            'slots' => 10,
            'start_date' => now()->subWeek(),
            'deadline' => now()->addMonth(),
            'status' => 'open',
        ], $overrides));

        $scholarship->requirements()->create([
            'requirement_type' => 'gwa',
            'requirement_condition' => 'less_than_or_equal',
            'required_value' => 2.00,
        ]);
        $scholarship->requiredDocuments()->create(['name' => 'Grade Report', 'is_required' => true]);

        return $scholarship;
    }

    private function makeDraft(User $user, Scholarship $scholarship): Application
    {
        return Application::create([
            'applicant_id' => $user->applicant->id,
            'scholarship_id' => $scholarship->id,
        ]);
    }

    public function test_every_sidebar_page_opens_for_an_applicant(): void
    {
        $user = $this->makeApplicant();
        $scholarship = $this->makeScholarship();

        foreach ([
            route('applicant.profile.edit'),
            route('applicant.scholarships.index'),
            route('applicant.scholarships.show', $scholarship),
            route('applicant.applications.index'),
            route('applicant.notifications.index'),
            route('applicant.search', ['q' => 'excellence']),
        ] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_other_roles_cannot_open_applicant_pages(): void
    {
        $evaluator = User::factory()->create();
        $evaluator->forceFill(['role' => 'evaluator'])->save();

        $this->actingAs($evaluator)->get(route('applicant.scholarships.index'))->assertForbidden();
        $this->actingAs($evaluator)->get(route('applicant.search', ['q' => 'abc']))->assertForbidden();
    }

    public function test_scholarship_list_hides_drafts_and_can_be_searched(): void
    {
        $user = $this->makeApplicant();
        $this->makeScholarship(['name' => 'Alpha Grant']);
        $this->makeScholarship(['name' => 'Beta Grant']);
        $this->makeScholarship(['name' => 'Hidden Draft Grant', 'status' => 'draft']);

        $this->actingAs($user)->get(route('applicant.scholarships.index'))
            ->assertSee('Alpha Grant')->assertSee('Beta Grant')->assertDontSee('Hidden Draft Grant');

        $this->actingAs($user)->get(route('applicant.scholarships.index', ['q' => 'Alpha']))
            ->assertSee('Alpha Grant')->assertDontSee('Beta Grant');
    }

    public function test_availability_filter_separates_open_and_closed(): void
    {
        $user = $this->makeApplicant();
        $this->makeScholarship(['name' => 'Open One']);
        $this->makeScholarship(['name' => 'Closed One', 'status' => 'closed', 'deadline' => now()->subDay()]);

        $this->actingAs($user)->get(route('applicant.scholarships.index', ['availability' => 'open']))
            ->assertSee('Open One')->assertDontSee('Closed One');

        $this->actingAs($user)->get(route('applicant.scholarships.index', ['availability' => 'closed']))
            ->assertSee('Closed One')->assertDontSee('Open One');
    }

    public function test_header_search_finds_scholarships_applications_and_notifications(): void
    {
        $user = $this->makeApplicant();
        $scholarship = $this->makeScholarship(['name' => 'Rizal Memorial Grant']);
        $this->makeDraft($user, $scholarship);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Rizal reminder',
            'message' => 'Do not forget your documents.',
        ]);

        $this->actingAs($user)->get(route('applicant.search', ['q' => 'Rizal']))
            ->assertOk()
            ->assertSee('Scholarships (1)', false)
            ->assertSee('My applications (1)', false)
            ->assertSee('Notifications (1)', false)
            ->assertSee('Rizal reminder');
    }

    public function test_search_never_shows_another_applicants_data(): void
    {
        $user = $this->makeApplicant();
        $other = $this->makeApplicant();
        $scholarship = $this->makeScholarship(['name' => 'Rizal Memorial Grant']);
        $this->makeDraft($other, $scholarship);
        Notification::create(['user_id' => $other->id, 'title' => 'Secret Rizal note', 'message' => 'private']);

        $this->actingAs($user)->get(route('applicant.search', ['q' => 'Rizal']))
            ->assertSee('My applications (0)', false)
            ->assertSee('Notifications (0)', false)
            ->assertDontSee('Secret Rizal note');
    }

    public function test_search_asks_for_at_least_two_characters(): void
    {
        $user = $this->makeApplicant();

        $this->actingAs($user)->get(route('applicant.search', ['q' => 'a']))
            ->assertOk()->assertSee('at least 2 characters');
    }

    public function test_applicant_can_apply_upload_submit_and_is_notified(): void
    {
        Storage::fake('local');
        $user = $this->makeApplicant();
        $scholarship = $this->makeScholarship();

        $this->actingAs($user)->post(route('applicant.applications.store', $scholarship))->assertRedirect();
        $application = Application::firstOrFail();
        $this->assertSame(ApplicationStatus::Draft, $application->status);

        // Missing document blocks the submission
        $this->actingAs($user)->post(route('applicant.applications.submit', $application))
            ->assertSessionHas('error');
        $this->assertSame(ApplicationStatus::Draft, $application->fresh()->status);

        $required = $scholarship->requiredDocuments()->first();
        $this->actingAs($user)->post(
            route('applicant.applications.documents.store', [$application, $required]),
            ['file' => UploadedFile::fake()->create('grades.pdf', 100, 'application/pdf')]
        )->assertSessionHasNoErrors();

        $this->actingAs($user)->post(route('applicant.applications.submit', $application))
            ->assertRedirect(route('applicant.applications.show', $application));

        $application->refresh();
        $this->assertSame(ApplicationStatus::Submitted, $application->status);
        $this->assertSame(EligibilityResult::Eligible, $application->eligibility_result);
        $this->assertEquals(1.75, $application->gwa);
        $this->assertNotNull($application->submitted_at);
        $this->assertDatabaseHas('app_notifications', ['user_id' => $user->id, 'title' => 'Application submitted']);
        $this->assertTrue(AuditLog::where('entity_id', $application->id)->where('action', 'submitted')->exists());
    }

    public function test_ineligible_application_is_saved_with_the_reasons(): void
    {
        Storage::fake('local');
        $user = $this->makeApplicant(['gwa' => 2.50]);
        $scholarship = $this->makeScholarship();
        $application = $this->makeDraft($user, $scholarship);

        $this->actingAs($user)->post(
            route('applicant.applications.documents.store', [$application, $scholarship->requiredDocuments()->first()]),
            ['file' => UploadedFile::fake()->create('grades.pdf', 50, 'application/pdf')]
        );
        $this->actingAs($user)->post(route('applicant.applications.submit', $application));

        $application->refresh();
        $this->assertSame(EligibilityResult::Ineligible, $application->eligibility_result);
        $this->assertSame(['GWA 2.50 is higher than the required 2.00.'], $application->eligibilityReasonList());
    }

    public function test_cannot_apply_twice_or_to_a_closed_scholarship(): void
    {
        $user = $this->makeApplicant();
        $open = $this->makeScholarship();
        $closed = $this->makeScholarship(['name' => 'Old Grant', 'status' => 'closed', 'deadline' => now()->subDay()]);

        $this->actingAs($user)->post(route('applicant.applications.store', $open));
        $this->actingAs($user)->post(route('applicant.applications.store', $open))->assertSessionHas('error');
        $this->assertSame(1, Application::where('scholarship_id', $open->id)->count());

        $this->actingAs($user)->post(route('applicant.applications.store', $closed))->assertSessionHas('error');
        $this->assertSame(0, Application::where('scholarship_id', $closed->id)->count());
    }

    public function test_applicant_cannot_see_or_change_someone_elses_application(): void
    {
        $owner = $this->makeApplicant();
        $stranger = $this->makeApplicant();
        $application = $this->makeDraft($owner, $this->makeScholarship());

        $this->actingAs($stranger)->get(route('applicant.applications.show', $application))->assertForbidden();
        $this->actingAs($stranger)->get(route('applicant.applications.edit', $application))->assertForbidden();
        $this->actingAs($stranger)->post(route('applicant.applications.cancel', $application))->assertForbidden();
        $this->assertSame(ApplicationStatus::Draft, $application->fresh()->status);
    }

    public function test_cancel_works_once_and_a_cancelled_application_is_final(): void
    {
        $user = $this->makeApplicant();
        $application = $this->makeDraft($user, $this->makeScholarship());

        $this->actingAs($user)->post(route('applicant.applications.cancel', $application))->assertSessionHas('success');
        $application->refresh();
        $this->assertSame(ApplicationStatus::Cancelled, $application->status);
        $this->assertNotNull($application->cancelled_at);

        $this->actingAs($user)->post(route('applicant.applications.cancel', $application))->assertSessionHas('error');
        $this->actingAs($user)->get(route('applicant.applications.edit', $application))
            ->assertRedirect(route('applicant.applications.show', $application));
    }

    public function test_evaluator_remarks_are_shown_only_once_the_evaluation_is_finalized(): void
    {
        $user = $this->makeApplicant();
        $application = $this->makeDraft($user, $this->makeScholarship());
        $evaluatorUser = User::factory()->create();
        $evaluatorUser->forceFill(['role' => 'evaluator'])->save();
        $evaluator = \App\Models\Evaluator::create(['user_id' => $evaluatorUser->id, 'employee_no' => 'EMP-T1']);

        \Illuminate\Database\Eloquent\Model::unguarded(function () use ($application, $evaluator) {
            $application->forceFill(['status' => ApplicationStatus::UnderEvaluation])->save();
            \App\Models\Evaluation::create([
                'application_id' => $application->id,
                'evaluator_id' => $evaluator->id,
                'remarks' => 'Draft remark that is not final',
                'is_finalized' => false,
            ]);
        });

        $this->actingAs($user)->get(route('applicant.applications.show', $application))
            ->assertOk()->assertDontSee('Draft remark that is not final');

        \Illuminate\Database\Eloquent\Model::unguarded(function () use ($application) {
            $application->evaluation->forceFill([
                'decision' => 'rejected',
                'score' => 55,
                'remarks' => 'Does not meet the GWA requirement.',
                'rejection_reason' => 'GWA is above the allowed maximum.',
                'is_finalized' => true,
            ])->save();
            $application->forceFill(['status' => ApplicationStatus::Rejected])->save();
        });

        $this->actingAs($user)->get(route('applicant.applications.show', $application))
            ->assertOk()
            ->assertSee('Does not meet the GWA requirement.')
            ->assertSee('GWA is above the allowed maximum.');
    }

    public function test_notifications_can_be_marked_read_and_only_by_their_owner(): void
    {
        $user = $this->makeApplicant();
        $other = $this->makeApplicant();
        $mine = Notification::create(['user_id' => $user->id, 'title' => 'Mine', 'message' => 'm']);
        $theirs = Notification::create(['user_id' => $other->id, 'title' => 'Theirs', 'message' => 't']);

        $this->actingAs($user)->post(route('applicant.notifications.read', $mine))->assertRedirect();
        $this->assertTrue($mine->fresh()->is_read);

        $this->actingAs($user)->post(route('applicant.notifications.unread', $mine));
        $this->assertFalse($mine->fresh()->is_read);

        $this->actingAs($user)->post(route('applicant.notifications.read', $theirs))->assertForbidden();
        $this->assertFalse($theirs->fresh()->is_read);

        $this->actingAs($user)->post(route('applicant.notifications.read-all'));
        $this->assertTrue($mine->fresh()->is_read);
        $this->assertFalse($theirs->fresh()->is_read);
    }

    public function test_student_profile_can_be_created_updated_and_password_changed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('applicant.profile.update'), [
            'student_no' => '2025-0001', 'course' => 'BSCS', 'gwa' => '1.50',
            'annual_family_income' => '120000', 'year_level' => '2',
        ])->assertRedirect(route('applicant.profile.edit'));

        $applicant = $user->fresh()->applicant;
        $this->assertSame('BSCS', $applicant->course);
        $this->assertEquals(1.5, $applicant->gwa);

        $this->actingAs($user)->patch(route('applicant.profile.update'), [
            'student_no' => '2025-0001', 'course' => 'BSCS', 'gwa' => '9',
        ])->assertSessionHasErrors('gwa');

        $this->actingAs($user)->put(route('applicant.profile.password'), [
            'current_password' => 'password',
            'password' => 'a-New-Passw0rd!',
            'password_confirmation' => 'a-New-Passw0rd!',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('a-New-Passw0rd!', $user->fresh()->password));
    }
}
