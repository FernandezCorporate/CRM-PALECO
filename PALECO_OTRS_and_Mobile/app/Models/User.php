<?php

namespace App\Models;

// Import Enum rules
use App\Enums\UserRole;                           
use App\Enums\LogName;                          
use App\Enums\LogDescription;    

use Illuminate\Foundation\Auth\User as Authenticatable;     // Provides the base user model for authentication.
use Illuminate\Notifications\Notifiable;                    // Enables notification functionality for the user model.
use Illuminate\Database\Eloquent\Factories\HasFactory;      // Enables support for database model factories.

use Spatie\Activitylog\Models\Concerns\LogsActivity;        // Enables automatic activity logging for model changes.
use Spatie\Activitylog\Support\LogOptions;                  // Provides configuration options for the Activitylog package.

class User extends Authenticatable
{
    // Traits for model features: factory support, notifications, and activity tracking.
    use HasFactory, Notifiable, LogsActivity;

    // Attributes that are mass-assignable via arrays or request data.
    protected $fillable = [
        'username', 'email', 'password', 'role', 
        'first_name', 'middle_name', 'last_name', 'name_ext',
    ];

    // Attributes that should be hidden from API responses or serialized output.
    protected $hidden = ['password', 'remember_token'];

    // Defines attribute type conversions for database interaction.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',             // Automatically hashes passwords using Bcrypt upon assignment.
            'role' => UserRole::class,          // Automatically casts the 'role' field to your UserRole Enum.
            'is_active' => 'boolean',           // Ensures the status is treated as a true/false value.
        ];
    }

    // Configures the Spatie Activitylog package for this specific model.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogName::USER_MANAGEMENT->value)       // Groups logs under the 'user_management' category.
            ->logFillable()                                     // Automatically logs changes to all mass-assignable attributes.
            ->dontLogIfAttributesChangedOnly(['password'])      // Prevents logging when only the password is updated.
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName));    // Maps system events to human-readable descriptions via Enum.
    }
}