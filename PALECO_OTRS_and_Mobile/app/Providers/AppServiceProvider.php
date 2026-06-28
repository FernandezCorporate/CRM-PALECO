<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;       // Provides the base class for all application service providers.
use Illuminate\Support\Facades\Gate;          // Facade for defining authorization logic and permission gates.
use Illuminate\Cache\RateLimiting\Limit;      // Defines rate limiting configurations and policies.
use Illuminate\Support\Facades\RateLimiter;   // Manages and enforces application-wide rate limiting rules.
use Illuminate\Http\Request;                  // Encapsulates the current HTTP request data.
use App\Models\User;                          // Imports the User model for role-based authorization.
use App\Enums\UserRole;                       // Imports the UserRole Enum for strict role checking.
use Spatie\Activitylog\Models\Activity;       // Imports the Activity model to intercept and modify logs.

class AppServiceProvider extends ServiceProvider
{
    // Bootstraps application services, such as authorization gates, global event listeners, and rate limiters.
    public function boot(): void
    {
        // Centralized authorization gate to verify if a user holds the Administrator role; used in '\routes\web.php'.
        Gate::define('access-admin', fn(User $user) => $user->role === UserRole::ADMIN);
        
        // Centralized authorization gate to verify if a user holds the CWD role; used in '\routes\web.php'.
        Gate::define('access-cwd', fn(User $user) => $user->role === UserRole::CWD);

        // Intercepts activity log creation to automatically inject metadata.
            Activity::creating(function (Activity $activity) {
                $activity->properties = $activity->properties->merge([
                    // Use the null coalescing operator to explicitly flag background/terminal jobs
                    'ip_address' => request()->ip() ?? 'CLI/System',
                    
                    // Crucial for identifying if the action came from the Web or the Flutter App
                    'user_agent' => request()->userAgent() ?? 'CLI/System'
                ]);
            });

        // Configures a rate limit policy allowing 20 login attempts per minute based on the requester's IP address.
        RateLimiter::for('login-ip', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        // Configures a rate limit policy allowing 5 login attempts per 15 minutes based on the provided username.
        RateLimiter::for('login-account', function (Request $request) {
            return Limit::perMinutes(15, 5)->by($request->input('username'));
        });
    }
}