<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 20)->unique();

            // Client credentials
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone', 50)->nullable();
            $table->string('company_name');
            $table->string('operations_country', 100)->nullable();
            $table->text('operational_challenges')->nullable();

            // Booking details
            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();
            $table->foreignId('consultant_id')
                ->nullable()
                ->constrained('consultants')
                ->nullOnDelete();
            $table->string('headcount_range', 50); // '1-5', '6-20', '20+'

            // Scheduling — always stored in UTC
            $table->dateTime('scheduled_at');       // UTC
            $table->string('client_timezone', 60)->default('UTC');
            $table->unsignedSmallInteger('duration_minutes')->default(30);

            // Meeting
            $table->string('meeting_link')->nullable();

            // Compliance / flags
            $table->boolean('nda_requested')->default(false);

            // Lifecycle
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                ->default('pending');
            $table->string('cancellation_reason')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'scheduled_at']);
            $table->index(['client_email']);
            $table->index(['consultant_id', 'scheduled_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
