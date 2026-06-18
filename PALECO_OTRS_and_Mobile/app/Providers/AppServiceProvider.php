<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Enums\UserRole;
use Spatie\Activitylog\Models\Activity; // 💡 Import this

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('access-admin', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        Gate::define('access-cwd', function (User $user) {
            return $user->role === UserRole::CWD;
        });

        // 💡 Global IP Logger: Intercepts every log entry before it saves
        Activity::creating(function (Activity $activity) {
            if (request()->ip()) {
                $activity->properties = $activity->properties->merge([
                    'ip_address' => request()->ip()
                ]);
            }
        });
    }
}