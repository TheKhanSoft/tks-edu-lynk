<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SessionRequest extends FormRequest
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
                'max:50',
                Rule::unique('sessions')->ignore($this->session)->whereNull('deleted_at'),
            ],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
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
            'name.required' => "Oh no! Every session needs a name. What would you like to call it?",
            'name.string' => "The session name should be plain text. No fancy symbols here, please!",
            'name.max' => "That's quite a long name! Could you keep it under 50 characters?",
            'name.unique' => "Oops! This session name already exists. How about something unique?",

            'start_date.date' => "The start date should be a valid date. Please check and try again.",
            'end_date.date' => "The end date should be a valid date. Please check and try again.",
            'end_date.after_or_equal' => "The end date must be on or after the start date.",

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
            'start_date' => $this->start_date ? trim($this->start_date) : null,
            'end_date' => $this->end_date ? trim($this->end_date) : null,
        ]);
    }
}