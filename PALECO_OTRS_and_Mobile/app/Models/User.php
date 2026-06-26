<?php

// Define the address of Team.php
namespace App\Models;

// Import defined Enum cases from LogName.php, LogDescription.php and UserRole.php
use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;

use Illuminate\Foundation\Auth\User as Authenticatable;     // An extended version of the Laravel Eloquent Model base class implemented with security methods
use Illuminate\Database\Eloquent\Factories\HasFactory;      // Provides a trait that allows the model to easily generate fake dummy data and seed default values into the database

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class User extends Authenticatable
{
    // Activate imported packages within the model class
    use HasFactory, LogsActivity;

    // Defines the columns that can store data received via mass assignment
    protected $fillable = [
        'username', 'email', 'password', 'role', 
        'department_id', 
        'first_name', 'middle_name', 'last_name', 'name_ext',
        'contact', 'last_login_at'
    ];

    // Defines the columns hidden when User records are queried
    protected $hidden = ['password', 'remember_token'];

    // Translates datatypes between the database and the Laravel application
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // Translates string datetime values from the database into readable datetime format for Laravel and vice versa
            'password' => 'hashed',             // Hashes the password data from the Laravel application before storing it on the database
            'role' => UserRole::class,          // Classes defined in UserRole.php are the only allowed inputs for 'role' column.
            'is_active' => 'boolean',           // Translates 0 and 1 from the 'is_active' column into PHP boolean values and vice versa
            'last_login_at' => 'datetime',      // Translates string datetime values from the database into readable datetime format for Laravel and vice versa
        ];
    }

    // Defines the configuration (how a user data alteration shall be audited) using the Spatie Activitylog package
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()                           // Starts a new configuration object from the Spatie package
            ->useLogName(LogName::USER_MANAGEMENT->value) // Assigns the value of the LogName::TEAN_MANAGEMENT to the'log_name' column in 'activity_logs when an event is recorded'
            ->logFillable()                                     // Tells the configuration object to track any changes made to the values defined in $fillable
            ->dontLogIfAttributesChangedOnly(['password'])      
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName));  // Calls the modelUpdated() method from LogDescription to create the description data of the event
    }

    // Table relationshps

    // User can only belong to ONE department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Users has a many-to-many relationship with teams (Users can belong to multiple teams) 
    // Joined through the 'team_members' table
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}