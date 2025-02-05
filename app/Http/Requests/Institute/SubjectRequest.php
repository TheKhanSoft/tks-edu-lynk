<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SubjectRequest extends FormRequest
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
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('subjects')->ignore($this->subject)->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->ignore($this->subject)->whereNull('deleted_at'),
                Rule::unique('subjects')->where('institute_id', $this->institute_id)
                    ->where('department_id', $this->department_id),
            ],
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
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
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
            'code.string' => "The subject code should be plain text. No fancy symbols here, please!",
            'code.max' => "That's quite a long code! Could you keep it under 50 characters?",
            'code.unique' => "Oops! This subject code is already taken. Time to get creative!",

            'name.required' => "Oh no! Every subject needs a name. What would you like to call it?",
            'name.string' => "The subject name should be plain text. No emojis or special formatting, please!",
            'name.max' => "That's quite a long name! Could you keep it under 255 characters?",
            'name.unique' => "Oops! This subject name already exists in your institute and department. How about something unique?",

            'institute_id.required' => "Every subject needs an institute. Please select one.",
            'institute_id.exists' => "The selected institute doesn't exist or isn't active. Let's pick another one.",

            'department_id.exists' => "The selected department doesn't exist or isn't active. Let's pick another one.",

            'type.string' => "The subject type should be plain text. No fancy symbols here, please!",
            'type.max' => "The subject type is getting a bit lengthy. Could you keep it under 100 characters?",

            'description.string' => "The description should be plain text. No emojis or special formatting, please!",
            'description.max' => "The description is getting a bit lengthy. Could you keep it under 1000 characters?",

            'is_active.boolean' => "The active status should be either 'Yes' or 'No'. No in-betweens!",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => $this->code ? trim(strtoupper($this->code)) : null, 
            'name' => trim(ucwords(strtolower($this->name))), 
            'type' => $this->type ? trim(ucwords(strtolower($this->type))) : null,
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
}