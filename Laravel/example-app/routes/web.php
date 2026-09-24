<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;
Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});
Route::get('/test', [TestController::class, 'test']);
require __DIR__.'/settings.php';
