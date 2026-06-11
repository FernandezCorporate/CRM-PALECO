<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 1. The Public Views
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest'); // Login page access route
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest'); // Submit Login credentials route

// 2. The Protected App Environment
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome'); // Authorized admin dashboard access route
    })->name('dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Logout route
});
