<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('institute_id')->constrained();
            $table->text('description')->nullable();
            $table->enum('frequency', ['one_time', 'monthly', 'quarterly', 'yearly']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_type_id')->constrained();
            $table->foreignId('class_section_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->date('applicable_from');
            $table->date('applicable_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('fee_structure_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            //Separate table for installment 
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_id')->constrained();
            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fees');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_types');
    }
};
