<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SectionRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections')->ignore($this->section)->whereNull('deleted_at'),
                Rule::unique('sections')->where('institute_id', $this->institute_id),
            ],
            'description' => 'nullable|string|max:1000',
            'institute_id' => [
                'required',
                'exists:institutes,id',
                Rule::exists('institutes', 'id')->where('is_active', true),
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
            'name.required' => "Oh no! Every section needs a name. What would you like to call it?",
            'name.string' => "The section name should be plain text. No fancy symbols here, please!",
            'name.max' => "That's quite a long name! Could you keep it under 255 characters?",
            'name.unique' => "Oops! This section name already exists in your institute. How about something unique?",

            'description.string' => "The description should be plain text. No emojis or special formatting, please!",
            'description.max' => "The description is getting a bit lengthy. Could you keep it under 1000 characters?",

            'institute_id.required' => "Every section needs a home! Please select the institute this section belongs to.",
            'institute_id.exists' => "Hmm, the selected institute doesn't exist or isn't active. Let's pick another one.",
            'institute_id.exists.institutes' => "Oh no! The selected institute isn't valid. Please try again.",

            'is_active.boolean' => "The active status should be either 'Yes' or 'No'. No in-betweens!",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim(ucwords(strtolower($this->name))),
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
}