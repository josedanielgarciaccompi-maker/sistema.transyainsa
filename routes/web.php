<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('home');
    Route::redirect('settings', 'settings/profile');

    
    Route::get('/conductor', App\Livewire\Conductor\Index::class)->name('conductor');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


});


require __DIR__.'/auth.php';
