<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class DepartmentRequest extends FormRequest
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
                Rule::unique('departments')->ignore($this->department)->whereNull('deleted_at'),
                Rule::unique('departments')->where('institute_id', $this->institute_id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('departments')
                    ->ignore($this->department)
                    ->whereNull('deleted_at'),
            ],
            'hod_id' => [
                'nullable',
                'exists:institute_staff,id',
                Rule::exists('institute_staff', 'id')
                    ->where('institutes', 'id')
                    ->where('is_active', true),
                
            ],
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
            'name.required' => "Hold up! Every department needs a name. What would you like to call it?",
            'name.string' => "The department name should be plain text. No fancy symbols here, please!",
            'name.max' => "Whoa there! That's quite a long name. Can we keep it under 255 characters?",
            'name.unique' => "Oops! This department name already exists in your institute. How about something unique?",

            'code.string' => "The department code should be plain text. No emojis allowed!",
            'code.max' => "That's a pretty long code. Could you shorten it to 50 characters or less?",
            'code.unique' => "This department code is already taken. Time to get creative!",

            'hod_id.exists' => "Hmm, it seems like the selected Head of Department doesn't exist or isn't active. Please choose someone else.",
            'hod_id.exists.staff' => "Uh-oh! The selected Head of Department isn't available. Try again.",

            'institute_id.required' => "Every department needs a home! Please select the institute this department belongs to.",
            'institute_id.exists' => "The selected institute doesn't exist or isn't active. Let's pick another one.",
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
            'code' => $this->code ? trim(strtoupper($this->code)) : null,
        ]);
    }
}