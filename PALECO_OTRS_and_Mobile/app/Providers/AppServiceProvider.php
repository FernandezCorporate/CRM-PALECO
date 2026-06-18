<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Authorization Gates
        Gate::define('access-admin', fn(User $user) => $user->role === UserRole::ADMIN);
        Gate::define('access-cwd', fn(User $user) => $user->role === UserRole::CWD);

        // 2. Global IP Logger: Intercepts every log entry
        Activity::creating(function (Activity $activity) {
            if (request()->ip()) {
                $activity->properties = $activity->properties->merge([
                    'ip_address' => request()->ip()
                ]);
            }
        });

        // 3. Rate Limiters
        // IP-Based: 5 attempts per 1 minute
        RateLimiter::for('login-ip', function (Request $request) {
            return RateLimiter::hit($request->ip(), 20);
        });

        // Account-Based: 5 attempts per 15 minutes
        RateLimiter::for('login-account', function (Request $request) {
            return RateLimiter::hit($request->input('username'), 5);
        });
    }
}