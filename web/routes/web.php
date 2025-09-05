<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ConjugationPractice;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/auth/redirect', function () {
    return Socialite::driver('smartschool')->redirect();
})->name('smartschool.redirect');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('smartschool')->user();

    // $user->token
});

Route::get('/practice', function () {
    return view('practice');
})->name('practice');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
