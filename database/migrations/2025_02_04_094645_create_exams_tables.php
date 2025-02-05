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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_session_id')->constrained("academic_sessions");
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');
            $table->dateTime('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue')->nullable();
            $table->decimal('total_marks', 5, 2);
            $table->decimal('passing_marks', 5, 2);
            $table->enum('exam_type', ['midterm', 'final', 'practical', 'quiz', ''])->default('final');
            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_date');

        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('exam_schedule_id')->constrained()->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['exam_id', 'student_id', 'exam_schedule_id'], 'unique_subject_student_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('exams');
    }
};
