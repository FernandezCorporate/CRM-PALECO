<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

use App\Http\Controllers\auth\AuthController;       
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\TeamController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('guest');

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return match (Auth::user()->role) {
            UserRole::ADMIN => redirect()->route('admin.pages.dashboard'),
            UserRole::CWD => redirect()->route('cwd.pages.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    Route::prefix('admin')->middleware(['auth', 'can:access-admin'])->group(function () {

        Route::get('/dashboard', function () { return view('admin.pages.dashboard'); })->name('admin.dashboard');

        Route::get('/users', [UserController::class, 'userManagement'])->name('admin.userManagement');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.toggleStatus');

        Route::prefix('users')->group(function () {
            Route::get('/create', [UserController::class, 'addUserForm'])->name('admin.addUserForm'); 
            Route::post('/create', [UserController::class, 'addUser'])->name('admin.addUser');
            Route::get('/edit/{user}', [UserController::class, 'updateUserForm'])->name('admin.updateUserForm');
            Route::put('/edit/{user}', [UserController::class, 'updateUser'])->name('admin.updateUser'); 
            Route::get('/{user}', [UserController::class, 'getUserDetails'])->name('admin.getUserDetails');
        });

        Route::get('/departments', [DepartmentController::class, 'deptManagement'])->name('admin.deptManagement');
        
        Route::prefix('departments')->group(function () {
            Route::get('/create', [DepartmentController::class, 'addDeptForm'])->name('admin.addDeptForm');
            Route::post('/create', [DepartmentController::class, 'addDept'])->name('admin.addDept');
            Route::get('/edit/{dept}', [DepartmentController::class, 'updateDeptForm'])->name('admin.updateDeptForm');
            Route::put('/edit/{dept}', [DepartmentController::class, 'updateDept'])->name('admin.updateDept');
        });

        // 💡 NEW: Global Team Routes
        Route::prefix('teams')->group(function () {
            Route::get('/', [TeamController::class, 'teamManagement'])->name('admin.teamManagement');
            Route::get('/{team}/members', [TeamController::class, 'teamMemberManagement'])->name('admin.teamMemberManagement');
        });
    });

    Route::prefix('cwd')->middleware(['auth', 'can:access-cwd'])->group(function () {
        Route::get('/dashboard', function () { return view('cwd.pages.dashboard'); })->name('cwd.dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});