<?php

use App\Http\Controllers\EvenController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::get('events/create', [EvenController::class, 'create'])->name('events.create');

    Route::post('events', [EvenController::class, 'store'])->name('events.store');

    Route::get('events/{event:slug}/edit', [EvenController::class, 'edit'])->name('events.edit');

    Route::patch('events/{event:slug}', [EvenController::class, 'update'])->name('events.update');

    Route::delete('events/{event:slug}', [EvenController::class, 'destroy'])->name('events.destroy');

    Route::post('events/{event:slug}/rsvp', [RsvpController::class, 'store'])->name('events.rsvp.store');

    Route::delete('events/{event:slug}/rsvp', [RsvpController::class, 'destroy'])->name('events.rsvp.destroy');
});

Route::get('/events', [EvenController::class, 'index'])->name('events.index');

Route::get('/events/{event:slug}',[EvenController::class, 'show'])->name('events.show');

require __DIR__.'/settings.php';
