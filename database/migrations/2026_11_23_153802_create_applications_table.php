<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->restrictOnDelete();
            $table->foreignId('scholarship_id')->constrained()->restrictOnDelete();

            // assignment
            $table->foreignId('assigned_evaluator_id')->nullable()->constrained('evaluators')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();

            // state
            $table->string('status')->default('draft');

            // eligibility result 
            $table->string('eligibility_result')->nullable();
            $table->text('eligibility_reasons')->nullable();

            // snapshot taken at submission
            $table->decimal('gwa', 3, 2)->nullable();
            $table->decimal('annual_family_income', 12, 2)->nullable();
            $table->unsignedTinyInteger('year_level')->nullable();

            // dates
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
