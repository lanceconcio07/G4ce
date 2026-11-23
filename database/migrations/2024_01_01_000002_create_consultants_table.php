<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('title')->default('Solutions Consultant');
            $table->string('avatar_url')->nullable();
            $table->string('timezone')->default('UTC');
            $table->unsignedTinyInteger('max_daily_bookings')->default(8);
            $table->boolean('is_active')->default(true);
            $table->string('meeting_link')->nullable()->comment('Personal Google Meet / Teams link');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};
