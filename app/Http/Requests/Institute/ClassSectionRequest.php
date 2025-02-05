<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class ClassSectionRequest extends FormRequest
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
            'department_id' => [
                'nullable',
                'exists:departments,id',
                Rule::exists('departments', 'id')->where('is_active', true),
            ],
            'class_id' => [
                'required',
                'exists:class_names,id',
                Rule::exists('class_names', 'id')->where('is_active', true),
            ],
            'section_id' => [
                'required',
                'exists:sections,id',
                Rule::exists('sections', 'id')->where('is_active', true),
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
            'institute_id.required' => "Every class section needs an institute. Please select one.",
            'institute_id.exists' => "The selected institute doesn't exist or isn't active. Let's pick another one.",
            'institute_id.exists.institutes' => "Oh no! The selected institute isn't valid. Please try again.",

            // 'department_id.required' => "Every class section needs a department. Please select one.",
            'department_id.exists' => "The selected department doesn't exist or isn't active. Let's pick another one.",
            'department_id.exists.departments' => "Uh-oh! The selected department isn't valid. Please try again.",

            'class_id.required' => "Every class section needs a class. Please select one.",
            'class_id.exists' => "The selected class doesn't exist or isn't active. Let's pick another one.",
            'class_id.exists.class_names' => "Oops! The selected class isn't valid. Please try again.",

            'section_id.required' => "Every class section needs a section. Please select one.",
            'section_id.exists' => "The selected section doesn't exist or isn't active. Let's pick another one.",
            'section_id.exists.sections' => "Hmm, the selected section isn't valid. Please try again.",

            'session_id.integer' => "The session ID should be a number. Please check and try again.",
            'session_id.exists' => "The selected session doesn't exist or isn't active. Let's pick another one.",
            'session_id.exists.sessions' => "Oh no! The selected session isn't valid. Please try again.",

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