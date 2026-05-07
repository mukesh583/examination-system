<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'semester_id' => ['nullable', 'exists:semesters,id'],
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:passed,failed,all'],
            'sort_by' => ['nullable', 'in:subject_name,marks,grade'],
            'sort_order' => ['nullable', 'in:asc,desc'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'semester_id.exists' => 'The selected semester does not exist.',
            'search.max' => 'Search query must not exceed 100 characters.',
            'status.in' => 'Status must be either passed, failed, or all.',
            'sort_by.in' => 'Sort field must be subject_name, marks, or grade.',
            'sort_order.in' => 'Sort order must be either asc or desc.',
        ];
    }
}
