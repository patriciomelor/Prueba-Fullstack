<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserManagement; 

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/users', UserManagement::class) 
     ->middleware(['auth', 'verified'])
     ->name('users.management');
Route::view('profile', 'profile')
    ->middleware(['auth']) 
    ->name('profile');

require __DIR__.'/auth.php';