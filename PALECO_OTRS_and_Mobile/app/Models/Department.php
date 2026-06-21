<?php

namespace App\Models;

use App\Enums\LogName;
use App\Enums\LogDescription;
use App\Enums\UserRole; // 💡 Ensure this is imported to use the Enum
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Department extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'dept_name', 
        'dept_description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()                                     
            ->useLogName(LogName::DEPARTMENT_MANAGEMENT->value) 
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName, 'Department'));
    }

    // Standard relationship for all employees
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // 💡 Specialized relationship for just the foremen
    public function foremen()
    {
        return $this->hasMany(User::class)->where('role', UserRole::FOREMAN);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}