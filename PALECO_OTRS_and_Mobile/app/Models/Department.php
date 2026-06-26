<?php

// Define the address of Department.php
namespace App\Models;

// Import defined Enum cases from LogName.php and LogDescription.php
use App\Enums\LogName;
use App\Enums\LogDescription;

use Illuminate\Database\Eloquent\Model;                     // Predefined; the base class for all Laravel Eloquent models
use Illuminate\Database\Eloquent\Factories\HasFactory;      // Provides a trait that allows the model to easily generate fake dummy data and seed default values into the database

use Spatie\Activitylog\Models\Concerns\LogsActivity;        // Spatie Package: Provides a trait that automatically logs data alteration for this model
use Spatie\Activitylog\Support\LogOptions;                  // Spatie Package: Provides configuration methods to define which database columns or events should be recorded in the log

class Department extends Model
{
    // Activate imported packages within the model class
    use HasFactory, LogsActivity;

    // Defines the columns that can store data received via mass assignment
    protected $fillable = [
        'dept_name', 
        'dept_description',
    ];

    // Defines the configuration (how a department data alteration shall be audited) using the Spatie Activitylog package
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()                               // Starts a new configuration object from the Spatie package
            ->logFillable()                                         // Tells the configuration object to track any changes made to the values defined in $fillable
            ->useLogName(LogName::DEPARTMENT_MANAGEMENT->value)     // Assigns the value of the LogName::DEPARTMENT_MANAGEMENT to the'log_name' column in 'activity_logs when an event is recorded'
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName, 'Department'));  // Calls the modelUpdated() method from LogDescription to create the description data of the event
    }

    // Table relationships

    // Department can consist of multiple users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Department can hold multiple teams
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}