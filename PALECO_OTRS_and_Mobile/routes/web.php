<?php

// Import controllers to extract the views they render/redirect to.
use App\Http\Controllers\AuthController;       
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\TeamController;


use Illuminate\Support\Facades\Route;           // Provides routing functions to map URLs to controller actions.
use Illuminate\Support\Facades\Auth;            // Provides authorization functions to identify the current user.
use App\Enums\UserRole;                         // Imports Enum roles for strict, type-safe authorization checks.

// Route for requesting the login page.
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest'); // Redirects authenticated users away from the login page.

// Route for submitting a login request.
Route::post('/login', [AuthController::class, 'authenticate'])
    ->middleware('guest'); // Ensures only unauthenticated users can attempt to log in.


Route::middleware(['auth'])->group(function () {

    // Central dashboard redirect based on user role.
    Route::get('/', function () {
        return match (Auth::user()->role) {
            UserRole::ADMIN => redirect()->route('admin.dashboard'),
            UserRole::CWD => redirect()->route('cwd.dashboard'),
            default => abort(403), // Terminates the request if the role has no defined dashboard.
        };
    })->name('dashboard');

    // Admin-specific routes protected by the 'access-admin' authorization gate in '\app\Providers\AppServiceProvider.php'.
    Route::prefix('admin')->middleware(['auth', 'can:access-admin'])->group(function () {

        // Displays the 'administrative dashboard' view.
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Displays the 'user management' view, listing all system users with search and filter capabilities.
        Route::get('/users', [UserController::class, 'userManagement'])
            ->name('admin.userManagement');

        Route::prefix('users')->group(function () {
            Route::get('/create', [UserController::class, 'addUserForm'])->name('admin.addUserForm'); 
            Route::post('/create', [UserController::class, 'addUser'])->name('admin.addUser');

            Route::get('/edit/{user}', [UserController::class, 'updateUserForm'])->name('admin.updateUserForm');
            Route::put('/edit/{user}', [UserController::class, 'updateUser'])->name('admin.updateUser');          
        });

        // Toggles the active status of a specific user.
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('admin.toggleStatus');
        
        Route::get('/departments', [DepartmentController::class, 'deptManagement'])
            ->name('admin.deptManagement');

        Route::post('/departments', [DepartmentController::class, 'addDept'])
            ->name('admin.addDept');

        Route::put('/departments/{dept}', [DepartmentController::class, 'updateDept'])
            ->name('admin.updateDept');

        Route::prefix('departments/{dept}')->group(function() {
            Route::get('/teams', [TeamController::class, 'teamManagement'])
            ->name('admin.teamManagement');
        });
    });

    // CWD-specific routes protected by the 'access-cwd' authorization gate in '\app\Providers\AppServiceProvider.php'.
    Route::prefix('cwd')->middleware(['auth', 'can:access-cwd'])->group(function () {
        
        // Displays the CWD dashboard view.
        Route::get('/dashboard', function () {
            return view('cwd.dashboard');
        })->name('cwd.dashboard');
    });

    // Logout route to invalidate the current user session.
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});