<?php

namespace Database\Seeders;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Enums\RequirementCondition;
use App\Enums\RequirementType;
use App\Enums\ScholarshipStatus;
use App\Enums\ScholarshipType;
use App\Models\Scholarship;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguarded(function () {
            $admin = User::where('email', 'admin@example.com')->firstOrFail();
            $logger = app(AuditLogger::class);

            $open = Scholarship::create([
                'name' => 'Academic Excellence Scholarship',
                'description' => 'For students with high grades and financial need.',
                'type' => ScholarshipType::Academic,
                'slots' => 10,
                'start_date' => now()->subMonth(),
                'deadline' => now()->addMonth(),
                'status' => ScholarshipStatus::Open,
            ]);

            $open->requirements()->createMany([
                ['requirement_type' => RequirementType::Gwa, 'requirement_condition' => RequirementCondition::LessThanOrEqual, 'required_value' => 2.00],
                ['requirement_type' => RequirementType::Income, 'requirement_condition' => RequirementCondition::LessThanOrEqual, 'required_value' => 250000],
                ['requirement_type' => RequirementType::YearLevel, 'requirement_condition' => RequirementCondition::GreaterThanOrEqual, 'required_value' => 2],
            ]);

            $open->requiredDocuments()->createMany([
                ['name' => 'Certificate of Registration', 'is_required' => true],
                ['name' => 'Grade Report', 'is_required' => true],
                ['name' => 'Proof of Income', 'is_required' => true],
            ]);

            $closed = Scholarship::create([
                'name' => 'Merit Scholarship (Closed)',
                'description' => 'Applications for this scholarship have ended.',
                'type' => ScholarshipType::Merit,
                'slots' => 5,
                'start_date' => now()->subMonths(4),
                'deadline' => now()->subMonths(2),
                'status' => ScholarshipStatus::Closed,
            ]);

            $closed->requirements()->create([
                'requirement_type' => RequirementType::Gwa,
                'requirement_condition' => RequirementCondition::LessThanOrEqual,
                'required_value' => 1.75,
            ]);

            $logger->log($admin, Action::Created, EntityType::Scholarship, $open->id, 'Created ' . $open->name);
            $logger->log($admin, Action::Created, EntityType::Scholarship, $closed->id, 'Created ' . $closed->name);
        });
    }
}