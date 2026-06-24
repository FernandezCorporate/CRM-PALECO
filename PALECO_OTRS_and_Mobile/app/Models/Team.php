<?php

namespace App\Models;

use App\Enums\LogName;
use App\Enums\LogDescription;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
class Team extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'team_name',
        'department_id',
        'is_active',
    ];

    protected function casts() : array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function shifts()
    {
        return $this->hasMany(TeamShift::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()                                     
            ->useLogName(LogName::TEAM_MANAGEMENT->value) 
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName, 'Team'));
    }
}
