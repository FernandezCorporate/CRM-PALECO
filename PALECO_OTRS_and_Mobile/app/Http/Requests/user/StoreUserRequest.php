<?php

// Defines the address of StoreUserRequest.php
namespace App\Http\Requests\user;

// Import Enum rules for valid user roles and day inputs
use App\Enums\UserRole;                        
use App\Enums\DayOfWeek;

use Illuminate\Foundation\Http\FormRequest;     // Predefined; the base class for custom, logic-rich form validation requests
use Illuminate\Validation\Rules\Enum;           // Provides methods for instantiating imported Enum classes
use Illuminate\Support\Str;                     // Provides string manipulation methods

class StoreUserRequest extends FormRequest
{
    // Sanitizes HTTP request data
    protected function prepareForValidation(): void
    {
        $this->merge([                                                                      // Overwrite values in HTTP request body
            'first_name'  => $this->first_name ? Str::lower($this->first_name) : null,      // first_name value is lowercased before validation; returns null if not provided
            'middle_name' => $this->middle_name ? Str::lower($this->middle_name) : null,    // middle_name value is lowercased before validation; returns null if not provided
            'last_name'   => $this->last_name ? Str::lower($this->last_name) : null,        // last_name value is lowercased before validation; returns null if not provided
            'name_ext'    => $this->name_ext ? Str::lower($this->name_ext) : null,          // name_ext value is lowercased before validation; returns null if not provided
            'email'       => $this->email ? Str::lower($this->email) : null,                // email value is lowercased before validation; returns null if not provided
            'username'    => $this->username ? trim($this->username) : null,                // Removes whitespaces on username value
        ]);
    }

    // Defines the validation rules that apply to the request data
    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:255'],                                   // Input is mandatory; max of 255 characters
            'middle_name'   => ['nullable', 'string', 'max:255'],                                   // Input is optional; max of 255 characters
            'last_name'     => ['required', 'string', 'max:255'],                                   // Input is mandatory; max of 255 characters
            'name_ext'      => ['nullable', 'string', 'max:10'],                                    // Input is optional; max of 10 characters
            'username'      => ['required', 'string', 'max:255', 'unique:users,username'],          // Mandatory; check if input is not on the username column for the users table
            'email'         => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],    // Optional; check if inputs is not on the email column for the users table
            'contact'       => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/'],                 // Mandatory; validates data if it follows the Philippine contact format (starts with 09 or +639)
            'password'      => ['required', 'string', 'min:8', 'confirmed'],                        // Mandatory; requires at least 8 characters and matches the 'password_confirmation' input field on the blade view
            'role'          => ['required', new Enum(UserRole::class)],                             // Mandatory; valid inputs are predefined based on the Enum rules in UserRole.php
            'department_id' => ['nullable', 'exists:departments,id'],                               // Optional; checks if department_id input is existing on the parent 'departments' table
            
            // User_shift child table inputs
            'shifts'                 => ['nullable', 'array'],                                      // Optional; expects an array of data (users can have multiple shifts)
            'shifts.*.day_of_week'   => ['required', new Enum(DayOfWeek::class)],                   // Mandatory if 'shifts' has value; predefined inputs based on the Enum rules in DayofWeek.php 
            'shifts.*.start_time'    => ['required', 'date_format:H:i'],                            // Mandatory if 'shifts' has value; 'H' requires 24-hour format (00-23) and 'i' allows for minutes with leading zeroes (00-59)
            'shifts.*.end_time'      => ['required', 'date_format:H:i'],                            // Mandatory if 'shifts' has value; 'H' requires 24-hour format (00-23) and 'i' allows for minutes with leading zeroes (00-59)
        ];
    }

    // Defines custom validation logic: Prevent overlapping schedules when shift boundaries cross midnight or roll over into the following week
    public function withValidator($validator)
    {
        // Runs ONLY AFTER standard rules pass
        $validator->after(function ($validator) {
    
            $shifts = $this->input('shifts', []);              // 1. Retrieve the 'shifts' array from the request; default to an empty array if missing
            if (empty($shifts) || !is_array($shifts)) return;  // 2. If no shifts were submitted, or the data isn't an array, exit early.

            // Initialize an array to hold all our converted, math-ready shift timelines
            $intervals = [];

            // LOOP 1: Convert all submitted shifts into "Absolute Minutes of the Week"
            foreach ($shifts as $index => $shift) {
                
                // 1. Check if all required shift fields are existing 
                if (!empty($shift['day_of_week']) && !empty($shift['start_time']) && !empty($shift['end_time'])) {
                    
                    // 2. Check if the time format contains a colon (e.g., '14:30') to prevent explode() errors
                    if (strpos($shift['start_time'], ':') !== false && strpos($shift['end_time'], ':') !== false) {
                         
                        // 3. Retrieve the DayofWeek instance based on the value of 'day_of_week' 
                        // (e.g. if 'day_of_week' == 'Monday', output is DayofWeek::MONDAY)
                        $dayEnum = DayOfWeek::tryFrom($shift['day_of_week']);   

                        // 4. Calls numericIndex() from DayofWeek.php to convert the day into its corresponding integer index; returns 0 if null
                        $dayIdx  = $dayEnum ? $dayEnum->numericIndex() : 0;     
                        
                        // 5. Explode the shift strings at the colon's position; separates Hours ($sH & $sM) and Minutes ($eH & $eM)
                        list($sH, $sM) = explode(':', $shift['start_time']);
                        list($eH, $eM) = explode(':', $shift['end_time']);
                        
                        // 6. Calculate the exact start and end times in "Absolute Minutes of the Week"
                        // (Formula: (Day * 24hrs * 60mins) + (Hours * 60mins) + Minutes)
                        $startMin = ($dayIdx * 24 * 60) + ($sH * 60) + (int)$sM;
                        $endMin = ($dayIdx * 24 * 60) + ($eH * 60) + (int)$eM;

                        // 7. If the shift ends earlier than it starts (e.g., 22:00 to 06:00), it crosses midnight
                        // We add 24 hours (1440 mins) to the end time to keep the timeline linear
                        if ($endMin <= $startMin) $endMin += (1440); 

                        // 8. There are 10,080 minutes in a week (7 * 24 * 60).
                        // If a late Sunday shift crosses midnight into Monday, it exceeds the weekly limit.
                        if ($endMin > 10080) {
                            
                            // Chunk 1: The portion of the shift from Sunday night until exactly 11:59 PM Sunday
                            $intervals[] = ['index' => $index, 'start' => $startMin, 'end' => 10080];
                            
                            // Chunk 2: The remaining portion rolling over into Monday morning (starting at minute 0)
                            $intervals[] = ['index' => $index, 'start' => 0, 'end' => $endMin - (10080)];
                        
                        } else {
                            // Standard shift: Just push the calculated interval into our array
                            $intervals[] = ['index' => $index, 'start' => $startMin, 'end' => $endMin];
                        }
                    }
                }
            }

            // Counts all items within the $interval array; used as the loop counter 
            $count = count($intervals);
            
            // LOOP 2: Check for overlaps across all parsed intervals
            // Nested loop to compare every shift against every other shift
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    
                    // 1. Prevent comparing a shift against itself, or against its own split chunk (from Sunday rollover)
                    if ($intervals[$i]['index'] === $intervals[$j]['index']) continue; 

                    // 2. The Overlap Formula: If the highest start time is strictly less than the lowest end time, the two shifts collide.
                    if (max($intervals[$i]['start'], $intervals[$j]['start']) < min($intervals[$i]['end'], $intervals[$j]['end'])) {
                        
                        // 3. Manually inject an error message targeting the exact row index (e.g., "shifts.1.start_time")
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