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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->string('name');
            $table->enum('type', ['earning', 'deduction']);
            $table->boolean('is_taxable')->default(false);
            $table->boolean('is_fixed')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('staff_salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('salary_component_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained();
            $table->date('payment_for_month');
            $table->date('payment_date');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('total_earnings', 10, 2);
            $table->decimal('total_deductions', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->json('component_details');
            $table->enum('status', ['pending', 'processed', 'paid']);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_salary_structures');
        Schema::dropIfExists('salary_components');
        Schema::dropIfExists('salary_payments');
    }
};
