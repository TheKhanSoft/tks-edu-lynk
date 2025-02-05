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
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('institute_id')->constrained();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->morphs('payable'); // For Fee, Salary, etc.
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online']);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->json('payment_details')->nullable(); // For storing payment gateway response
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
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_types');
    }
};
