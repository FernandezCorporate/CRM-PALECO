<?php

// Define the address of UserShift.php
namespace App\Models;

// Import defined Enum cases from DayofWeek.php
use App\Enums\DayOfWeek;

// Predefined; the base class for all Laravel Eloquent models
use Illuminate\Database\Eloquent\Model;

class UserShift extends Model
{
    // Defines the columns that can store data received via mass assignment
    protected $fillable = ['user_id', 'day_of_week', 'start_time', 'end_time'];

    // Translates datatypes between the database and the Laravel application
    protected function casts(): array
    {
        return ['day_of_week' => DayOfWeek::class];     // Classes defined in DayofWeek.php are the only allowed inputs for 'day_of_week' column.
    }

    // Table relationships

    // A single shift schedule belongs to ONE user only
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}