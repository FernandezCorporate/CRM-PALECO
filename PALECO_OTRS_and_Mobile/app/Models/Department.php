<?php

namespace App\Models;

use App\Enums\LogName;                               // Imports the Enum for strict category management.
use App\Enums\LogDescription;                        // Imports the Enum for standardized log messaging.
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Concerns\LogsActivity; // Enables automatic activity logging.
use Spatie\Activitylog\Support\LogOptions;           // Provides configuration options for activity tracking.

class Department extends Model
{
    use HasFactory, LogsActivity;

    // Attributes that are mass-assignable via arrays or request data.
    protected $fillable = [
        'dept_name', 
        'dept_description',
    ];

    // Configures the Spatie Activitylog package for this specific model.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()                                
            // 💡 Enforces strict log categorization using your LogName Enum
            ->useLogName(LogName::DEPARTMENT_MANAGEMENT->value) 
            // 💡 Passes 'Department' to your updated dynamic helper for clear, readable logs
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName, 'Department'));
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}