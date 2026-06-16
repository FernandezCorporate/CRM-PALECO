<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Enums\UserRole;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define the gate rule here
        Gate::define('access-admin', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        Gate::define('access-cwd', function (User $user) {
            return $user->role === UserRole::CWD;
        });
    }
}
