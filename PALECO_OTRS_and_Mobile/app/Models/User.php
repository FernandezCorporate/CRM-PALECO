<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\LogName;
use App\Enums\LogDescription;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'username', 'email', 'password', 'role', 
        'department_id', 'shift_start', 'shift_end',
        'first_name', 'middle_name', 'last_name', 'name_ext',
        'contact',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogName::USER_MANAGEMENT->value)
            ->logFillable()
            ->dontLogIfAttributesChangedOnly(['password'])
            ->setDescriptionForEvent(fn(string $eventName) => LogDescription::modelUpdated($eventName));
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}