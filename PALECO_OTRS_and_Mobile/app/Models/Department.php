<?php

namespace App\Models;

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
            ->logFillable()                                // Automatically logs changes to all fillable attributes.
            ->useLogName('department_management');         // Categorizes department changes for easier auditing.
    }
}