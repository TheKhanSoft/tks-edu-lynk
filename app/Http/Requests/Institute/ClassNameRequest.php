<?php

namespace App\Http\Requests\Institute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class ClassNameRequest extends FormRequest
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
                Rule::unique('classes')
                    ->where('institute_id', $this->institute_id)
                    ->ignore($this->className)
                    ->whereNull('deleted_at')
            ],
            'description' => 'nullable|string|max:1000',
            'institute_id' => [
                'required',
                'exists:institutes,id',
                Rule::exists('institutes', 'id')
                    ->where('is_active', true)
            ],
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Whoops! Every class needs a name! What would you like to call it?',
            'name.string' => 'The class name should be a text string. Please check and try again.',
            'name.max' => 'The class name is too long. Keep it under 255 characters.',
            'name.unique' => 'Oops! It seems like this class name is already taken in your institute. Could you pick a different one?',
            
            'description.string' => 'The description should be a text string. Please check and try again.',
            'description.max' => 'The description is too detailed. Please keep it under 1000 characters.',
            
            'institute_id.required' => 'Please specify which institute this class belongs to.',
            'institute_id.exists.institutes' => 'The selected institute doesn’t seem to exist or is currently inactive. Please choose a different one.',
            'institute_id.exists' => 'The selected institute doesn\'t exist or is inactive.',
            
            'is_active.boolean' => 'The active status should be either true or false. Please check and try again.',
        ];
        
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim(ucwords(strtolower($this->name))),
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }
}
