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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->decimal('price', 10, 2);
            $table->integer('duration')->default(30);
            $table->mediumText('description')->nullable();
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscription_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150); //active, inactive, pending, expired, extended
            $table->mediumText('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('institute_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('subscription_id')->constrained();
            $table->date('purchase_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('extended_date')->nullable();
            $table->foreignId('subscription_status_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('institute_subscribed_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_subscription_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['institute_subscription_id', 'module_id'], 'unique_institute_sub_module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_subscribed_modules');
        Schema::dropIfExists('institute_subscriptions');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('subscription_statuses');
        Schema::dropIfExists('subscriptions');
    }
};
