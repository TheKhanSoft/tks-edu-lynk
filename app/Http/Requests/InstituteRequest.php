<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class InstituteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('institutes')->where(function ($query) {
                    return $query->where('city', $this->city)
                                ->where('deleted_at', null);
                })->ignore($this->institute)
            ],
            'type' => ['required', Rule::in(['school', 'college', 'university'])],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:150'],
            'state' => ['required', 'string', 'max:150'],
            'postal_code' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-]+$/'],
            'country' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:80',
                Rule::unique('institutes')->ignore($this->institute)
            ],
            'contact_no' => ['nullable', 'string', 'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'],
            'extra_contacts' => ['nullable', 'array'],
            // 'extra_contacts.*.type' => ['required_with:extra_contacts', Rule::in(['phone', 'email', 'fax'])],
            // 'extra_contacts.*.value' => ['required_with:extra_contacts'],
            'is_active' => ['boolean']
        ];

        // Additional rules for update
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'] = ['nullable', 'email:rfc,dns', 'max:80'];
        }

        return $rules;
    }

    public function messages(): array
    {

        return [
            'name.required' => 'Every institute needs a name! Please provide one.',
            'name.string' => 'The institute name should be a text string. Please check and try again.',
            'name.max' => 'The institute name is too long. Could you keep it under 255 characters?',
            'name.unique' => 'Looks like there\'s already an institute with this name in :city ! Could you pick a different one?',

            'type.required' => 'What kind of institute is this? Please select school, college, or university',
            'type.in' => 'The institute type must be one of: school, college, or university.',

            'address.required' => 'We need the institute\'s address to help people find it. Please provide one.',
            'address.string' => 'The address should be a text string. Please check and try again.',
            'address.max' => 'The address is too long. Could you keep it under 255 characters?',

            'city.required' => 'Don\'t forget to tell us which city the institute is in',
            'city.string' => 'The city name should be a text string. Please check and try again.',
            'city.max' => 'The city name is too long. Could you keep it under 150 characters?',

            'state.required' => 'Please let us know which state/province the institute is located in',
            'state.string' => 'The state name should be a text string. Please check and try again.',
            'state.max' => 'The state name is too long. Could you keep it under 150 characters?',

            'postal_code.string' => 'The postal code should be a text string. Please check and try again.',
            'postal_code.regex' => 'The postal code should only contain numbers and hyphens',
            'postal_code.max' => 'The postal code is too long. Could you keep it under 20 characters?',

            'country.required' => 'Please specify the country where the institute is located.',
            'country.string' => 'The country name should be a text string. Please check and try again.',
            'country.max' => 'The country name is too long. Could you keep it under 100 characters?',
            'email.email' => 'That email address doesn\'t look quite right. Could you double-check it?',
            'email.unique' => 'This email address is already associated with another institute. Could you use a different one?',
            'email.max' => 'The email address is too long. Could you keep it under 80 characters?',

            'contact_no.string' => 'The contact number should be a text string. Please check and try again.',
            'contact_no.max' => 'The contact number is too long. Could you keep it under 20 characters?',
            'contact_no.regex' => 'Please provide a valid contact number (e.g., +92-321-1234567)',

            'extra_contacts.json' => 'The extra contacts field must be a valid JSON object. Please check and try again.',
            // 'extra_contacts.*.type.in' => 'Additional contact must be either a phone number, email, or fax',
            // 'extra_contacts.*.value.required_with' => 'Please provide a value for each additional contact',

            'is_active.boolean' => 'The active status must be either true or false.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => ucwords(strtolower($this->name))
            ]);
        }

        $this->merge([
            'name' => trim(ucwords(strtolower($this->name))),
            'address' => trim(ucwords(strtolower($this->address))),
            'city' => trim(ucwords(strtolower($this->city))),
            'state' => trim(ucwords(strtolower($this->state))),
            'country' => trim(ucwords(strtolower($this->country ?? 'Pakistan'))), // Default to Pakistan if not provided
            'email' => $this->email ? trim(strtolower($this->email)) : null,
            'contact_no' => $this->contact_no ? trim($this->contact_no) : null,
        ]);
    }

    
}


