<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Model;

class UserShift extends Model
{
    protected $fillable = ['user_id', 'day_of_week', 'start_time', 'end_time'];

    protected function casts(): array
    {
        return ['day_of_week' => DayOfWeek::class];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}