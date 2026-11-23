<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home — redirect to booking portal
Route::get('/', fn () => redirect()->route('booking.index'));

// Booking portal
Route::prefix('book')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
});

// API endpoints (used by Livewire AJAX calls internally — no extra auth needed)
Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    // Expose available slots for a given date + timezone (public JSON endpoint for JS fallback)
    Route::get('/available-slots', [BookingController::class, 'availableSlots'])->name('api.slots');

    // AI FAQ Gemini query endpoint
    Route::post('/faq/ask', [FaqController::class, 'ask'])->name('api.faq');
});
