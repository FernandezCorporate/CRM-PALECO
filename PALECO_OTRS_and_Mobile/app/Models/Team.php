<?php

// Define the address of Team.php
namespace App\Models;

// Import defined Enum cases from LogName.php and LogDescription.php
use App\Enums\LogName;
use App\Enums\LogDescription;

use Illuminate\Database\Eloquent\Model;                     // Predefined; the base class for all Laravel Eloquent models
use Illuminate\Database\Eloquent\Factories\HasFactory;      // Provides a trait that allows the model to easily generate fake dummy data and seed default values into the database

use Spatie\Activitylog\Models\Concerns\LogsActivity;        // Spatie Package: Provides a trait that automatically logs data alteration for this model
use Spatie\Activitylog\Support\LogOptions;                  // Spatie Package: Provides configuration methods to define which database columns or events should be recorded in the log

class Team extends Model
{
    // Activate imported packages within the model class
    use HasFactory, LogsActivity;

    // Defines the columns that can store data received via mass assignment
    protected $fillable = [
        'team_name',
        'department_id',
        'is_active',
        'shift_start',
        'shift_end',
    ];

    // Translates datatypes between the database and the Laravel application
    protected function casts() : array
    {
        return [
            'is_active' => 'boolean',    // Translates 0 and 1 from the 'is_active' column into PHP boolean values and vice versa
        ];
    }

    // Table relationships

    // Team belongs to only ONE department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Team has a many-to-many relationship with users (Teams can have multiple users/members) 
    // Joined through the 'team_members' table
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Defines the configuration (how a team data alteration shall be audited) using the Spatie Activitylog package
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()                           // Starts a new configuration object from the Spatie package
            ->logFillable()                                     // Tells the configuration object to track any changes made to the values defined in $fillable 
            ->useLogName(LogName::TEAM_MANAGEMENT->value)       // Assigns the value of the LogName::TEAN_MANAGEMENT to the'log_name' column in 'activity_logs when an event is recorded'
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName, 'Team'));  // Calls the modelUpdated() method from LogDescription to create the description data of the event
    }
}
