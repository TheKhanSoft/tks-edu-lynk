<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class ClassSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'institute_id' => [
                'required',
                'exists:institutes,id',
                Rule::exists('institutes', 'id')->where('is_active', true),
            ],
            'class_section_id' => [
                'required',
                'exists:class_sections,id',
                Rule::exists('class_sections', 'id')->where('is_active', true),
            ],
            'subject_id' => [
                'required',
                'exists:subjects,id',
                Rule::exists('subjects', 'id')->where('is_active', true),
            ],
            'teacher_id' => [
                'nullable',
                'exists:staff,id',
                Rule::exists('staff', 'id')->where('is_active', true),
            ],
            'session_id' => [
                'nullable',
                'integer',
                Rule::exists('sessions', 'id')->where('is_active', true),
            ],
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'institute_id.required' => "Every class subject needs an institute. Please select one.",
            'institute_id.exists' => "The selected institute doesn't exist or isn't active. Let's pick another one.",

            'class_section_id.required' => "Every class subject needs a class section. Please select one.",
            'class_section_id.exists' => "The selected class section doesn't exist or isn't active. Let's pick another one.",

            'subject_id.required' => "Every class subject needs a subject. Please select one.",
            'subject_id.exists' => "The selected subject doesn't exist or isn't active. Let's pick another one.",

            'teacher_id.exists' => "The selected teacher doesn't exist or isn't active. Let's pick another one.",

            'session_id.integer' => "The session ID should be a number. Please check and try again.",
            'session_id.exists' => "The selected session doesn't exist or isn't active. Let's pick another one.",

            'is_active.boolean' => "The active status should be either 'Yes' or 'No'. No in-betweens!",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'session_id' => $this->session_id ? (int) $this->session_id : null,
        ]);
    }
}