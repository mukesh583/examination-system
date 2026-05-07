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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2);
            $table->string('grade', 2);
            $table->boolean('is_passed')->default(false);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate results
            $table->unique(['student_id', 'semester_id', 'subject_id'], 'unique_student_semester_subject');
            
            // Composite indexes for performance
            $table->index(['student_id', 'semester_id']);
            $table->index(['semester_id', 'subject_id']);
            $table->index('is_passed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
