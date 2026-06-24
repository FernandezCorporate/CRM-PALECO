<?php

namespace App\Http\Requests\team;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'team_name' => ['required', 'string', 'max:255', 'unique:teams,team_name'],
            'department_id' => ['required', 'exists:departments,id'],
            'shift_start' => ['nullable', 'string'],
            'shift_end' => ['nullable', 'string'],
        ];
    }
}
