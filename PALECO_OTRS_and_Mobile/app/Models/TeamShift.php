<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Model;

class TeamShift extends Model
{
    protected $fillable = ['team_id', 'day_of_week', 'start_time', 'end_time'];

    protected function casts(): array
    {
        return ['day_of_week' => DayOfWeek::class];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}