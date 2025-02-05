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
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('institute_id')->constrained();
            $table->text('description')->nullable();
            $table->decimal('monthly_fee', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transport_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('transport_routes');
            $table->string('stop_name');
            $table->time('pickup_time');
            $table->time('drop_time');
            $table->integer('stop_order');
            $table->timestamps();
        });

        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_no')->unique();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('route_id')->constrained('transport_routes');
            $table->string('vehicle_type');
            $table->integer('capacity');
            $table->string('driver_name');
            $table->string('driver_phone');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transport_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('route_id')->constrained('transport_routes');
            $table->foreignId('stop_id')->constrained('transport_stops');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_assignments');
        Schema::dropIfExists('transports');
        Schema::dropIfExists('transport_stops');
        Schema::dropIfExists('transport_routes');
    }
};
