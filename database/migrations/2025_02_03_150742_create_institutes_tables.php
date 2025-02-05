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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['school', 'college', 'university']);
            $table->string('address', 255);
            $table->string('city', 150);
            $table->string('state', 150);
            $table->string('postal_code', 20)->nullable();
            $table->string('country')->default('Pakistan');
            $table->string('email', 80)->unique()->nullable();
            $table->string('contact_no')->nullable();
            $table->json('extra_contacts')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('academic_year',20)->nullable();
            $table->string('short_name', 10)->nullable();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'name']);
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            //$table->foreignId('hod_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'name']);
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('father_name', 120)->nullable();
            $table->string('nic_no', 20)->nullable();
            $table->enum('gender',['Male', 'Female', 'Other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email', 80)->unique()->nullable();
            $table->string('contact_no')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'department_id', 'nic_no']);
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('father_name', 120)->nullable();
            $table->string('nic_no', 20)->nullable();
            $table->enum('gender',['Male', 'Female', 'Other'])->nullable();
            $table->date('date_of_birth')->nullable();
             $table->string('email', 80)->unique()->nullable();
            $table->string('contact_no')->nullable();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'name', 'father_name', 'nic_no'], 'unique_student_per_class');
        });

        Schema::create('class_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'name']);
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // A, B, C, etc. 2021-2022, 2022-2023; Pink, Red, Yellow, etc. 2023-2024
            $table->text('description')->nullable();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'name']);
        });
    
        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained("class_names")->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->integer('academic_sessions_id')->nullable()->constrained()->onDelete('cascade'); //2021-2022
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'department_id', 'class_id', 'section_id', 'academic_sessions_id'], 'unique_class_section');
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'department_id', 'code', 'name']);
        });

        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->integer('academic_sessions_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_id', 'class_section_id', 'subject_id', 'teacher_id', 'academic_sessions_id'],'unique_class_subject');
        });

        
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('hod_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete()
                ->after('name)');
            ;
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign('hod_id'); 
        });
        Schema::dropIfExists('class_subjects');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('class_sections');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('class_names');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('institutes');
    }
};
