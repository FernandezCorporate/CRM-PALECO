<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'authenticate'])
    ->middleware('guest');

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return match (Auth::user()->role) {
            UserRole::ADMIN => redirect()->route('admin.dashboard'),
            UserRole::CWD => redirect()->route('cwd.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    Route::prefix('admin')->middleware(['auth', 'can:access-admin'])->group(function () {

        Route::get('/users', [AdminController::class, 'userManagement'])
            ->name('admin.userManagement');

        // CREATE
        Route::post('/users', [AdminController::class, 'addUser'])
            ->name('admin.addUser');

        // UPDATE
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])
            ->name('admin.updateUser');
    });

    Route::prefix('cwd')->middleware(['auth', 'can:access-cwd'])->group(function () {
        Route::get('/dashboard', function () {
            return view('cwd.dashboard');
        })->name('cwd.dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});