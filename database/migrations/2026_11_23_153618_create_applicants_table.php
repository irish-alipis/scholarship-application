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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('student_no')->unique();
            $table->string('course');
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('gwa', 3, 2)->nullable();
            $table->decimal('annual_family_income', 12, 2)->nullable();
            $table->unsignedTinyInteger('year_level')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
