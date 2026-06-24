<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Enums\DayOfWeek;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Str;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name'  => $this->first_name ? Str::lower($this->first_name) : null,
            'middle_name' => $this->middle_name ? Str::lower($this->middle_name) : null,
            'last_name'   => $this->last_name ? Str::lower($this->last_name) : null,
            'name_ext'    => $this->name_ext ? Str::lower($this->name_ext) : null,
            'email'       => $this->email ? Str::lower($this->email) : null,
            'username'    => $this->username ? trim($this->username) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:255'],
            'middle_name'   => ['nullable', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'name_ext'      => ['nullable', 'string', 'max:10'],
            'username'      => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'         => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'contact'       => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', new Enum(UserRole::class)],
            'department_id' => ['nullable', 'exists:departments,id'],
            
            // 💡 Notice 'after:start_time' is removed to allow overnight shifts
            'shifts'                 => ['nullable', 'array'],
            'shifts.*.day_of_week'   => ['required', new Enum(DayOfWeek::class)],
            'shifts.*.start_time'    => ['required', 'date_format:H:i'],
            'shifts.*.end_time'      => ['required', 'date_format:H:i'], 
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $shifts = $this->input('shifts', []);
            if (empty($shifts) || !is_array($shifts)) return;

            $dayMap = [
                DayOfWeek::MONDAY->value => 0, DayOfWeek::TUESDAY->value => 1, 
                DayOfWeek::WEDNESDAY->value => 2, DayOfWeek::THURSDAY->value => 3, 
                DayOfWeek::FRIDAY->value => 4, DayOfWeek::SATURDAY->value => 5, DayOfWeek::SUNDAY->value => 6
            ];

            $intervals = [];

            // Convert all shifts to absolute minutes of the week
            foreach ($shifts as $index => $shift) {
                if (!empty($shift['day_of_week']) && !empty($shift['start_time']) && !empty($shift['end_time'])) {
                    if (strpos($shift['start_time'], ':') !== false && strpos($shift['end_time'], ':') !== false) {
                        $dayIdx = $dayMap[$shift['day_of_week']] ?? 0;
                        list($sH, $sM) = explode(':', $shift['start_time']);
                        list($eH, $eM) = explode(':', $shift['end_time']);
                        
                        $startMin = ($dayIdx * 24 * 60) + ($sH * 60) + (int)$sM;
                        $endMin = ($dayIdx * 24 * 60) + ($eH * 60) + (int)$eM;

                        if ($endMin <= $startMin) $endMin += (24 * 60); // It crosses midnight

                        // Handle Sunday rollover into Monday
                        if ($endMin > 7 * 24 * 60) {
                            $intervals[] = ['index' => $index, 'start' => $startMin, 'end' => 7 * 24 * 60];
                            $intervals[] = ['index' => $index, 'start' => 0, 'end' => $endMin - (7 * 24 * 60)];
                        } else {
                            $intervals[] = ['index' => $index, 'start' => $startMin, 'end' => $endMin];
                        }
                    }
                }
            }

            // Check for overlaps across all parsed intervals
            $count = count($intervals);
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    if ($intervals[$i]['index'] === $intervals[$j]['index']) continue; 

                    if (max($intervals[$i]['start'], $intervals[$j]['start']) < min($intervals[$i]['end'], $intervals[$j]['end'])) {
                        $validator->errors()->add(
                            "shifts.{$intervals[$j]['index']}.start_time", 
                            "This shift overlaps with another schedule in your list."
                        );
                    }
                }
            }
        });
    }
}