<?php

namespace App\Http\Requests;

use App\Models\Result;
use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
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
            'student_id' => ['required', 'exists:users,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'marks_obtained' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->subject_id) {
                        $subject = Subject::find($this->subject_id);
                        if ($subject && $value > $subject->max_marks) {
                            $fail("Marks cannot exceed {$subject->max_marks} for this subject.");
                        }
                    }
                },
            ],
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
            // Check for duplicate result
            if ($this->student_id && $this->semester_id && $this->subject_id) {
                $exists = Result::where('student_id', $this->student_id)
                    ->where('semester_id', $this->semester_id)
                    ->where('subject_id', $this->subject_id)
                    ->exists();
                
                if ($exists) {
                    $validator->errors()->add('duplicate', 'A result already exists for this student, semester, and subject combination.');
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
            'student_id.exists' => 'The selected student does not exist.',
            'semester_id.exists' => 'The selected semester does not exist.',
            'subject_id.exists' => 'The selected subject does not exist.',
            'marks_obtained.min' => 'Marks cannot be negative.',
        ];
    }
}
