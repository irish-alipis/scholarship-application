<?php

namespace Database\Seeders;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Applicant;
use App\Models\Evaluator;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguarded(function () {
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => 'password',
                'role' => Role::Admin,
                'status' => AccountStatus::Active,
                'email_verified_at' => now(),
            ]);

            $evaluators = [
                ['Dr. Elena Cruz', 'evaluator1@example.com', 'EMP-001', AccountStatus::Active],
                ['Prof. Mark Tan', 'evaluator2@example.com', 'EMP-002', AccountStatus::Active],
                ['Ms. Carla Ramos', 'evaluator3@example.com', 'EMP-003', AccountStatus::Pending],
            ];

            foreach ($evaluators as [$name, $email, $employeeNo, $status]) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => 'password',
                    'role' => Role::Evaluator,
                    'status' => $status,
                    'email_verified_at' => now(),
                ]);
                Evaluator::create(['user_id' => $user->id, 'employee_no' => $employeeNo]);
            }

            // name, email, student no, course, gwa (1.0 is best), income, year level
            $applicants = [
                ['Maria Santos', 'maria@example.com', '2023-0001', 'BSIT', 1.50, 150000, 3],
                ['Juan Dela Cruz', 'juan@example.com', '2023-0002', 'BSCS', 1.75, 200000, 2],
                ['Ana Reyes', 'ana@example.com', '2023-0003', 'BSIT', 2.50, 120000, 3],
                ['Pedro Lim', 'pedro@example.com', '2023-0004', 'BSCS', 1.90, 400000, 4],
                ['Liza Gomez', 'liza@example.com', '2024-0005', 'BSIT', 1.25, 90000, 1],
            ];

            foreach ($applicants as [$name, $email, $studentNo, $course, $gwa, $income, $year]) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => 'password',
                    'role' => Role::Applicant,
                    'status' => AccountStatus::Active,
                    'email_verified_at' => now(),
                ]);
                Applicant::create([
                    'user_id' => $user->id,
                    'student_no' => $studentNo,
                    'course' => $course,
                    'gwa' => $gwa,
                    'annual_family_income' => $income,
                    'year_level' => $year,
                ]);
            }
        });
    }
}