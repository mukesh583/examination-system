<?php

namespace App\Http\Requests;

use App\Models\GradeScale;
use Illuminate\Foundation\Http\FormRequest;

class StoreGradeScaleRequest extends FormRequest
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
            'grade' => ['required', 'string', 'max:5', 'unique:grade_scales,grade'],
            'min_marks' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_marks' => ['required', 'numeric', 'min:0', 'max:100', 'gte:min_marks'],
            'grade_point' => ['required', 'numeric', 'min:0', 'max:10'],
            'is_passing' => ['required', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for overlapping marks ranges
            if ($this->min_marks !== null && $this->max_marks !== null) {
                $overlaps = GradeScale::where(function ($query) {
                    $query->whereBetween('min_marks', [$this->min_marks, $this->max_marks])
                          ->orWhereBetween('max_marks', [$this->min_marks, $this->max_marks])
                          ->orWhere(function ($q) {
                              $q->where('min_marks', '<=', $this->min_marks)
                                ->where('max_marks', '>=', $this->max_marks);
                          });
                })->exists();
                
                if ($overlaps) {
                    $validator->errors()->add('overlap', 'The marks range overlaps with an existing grade scale.');
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'grade.unique' => 'This grade already exists.',
            'max_marks.gte' => 'Maximum marks must be greater than or equal to minimum marks.',
            'grade_point.max' => 'Grade point cannot exceed 10.',
        ];
    }
}
