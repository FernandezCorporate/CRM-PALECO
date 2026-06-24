<?php

namespace App\Http\Requests\department;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dept = $this->route('dept');

        return [
            'dept_name' => ['required', 'string', 'max:255', 'unique:departments,dept_name,' . $dept->id],
            'dept_description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
