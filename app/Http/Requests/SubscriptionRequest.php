<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubscriptionRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'required',
                'string',
            ],
            'price' => [
                'required',
                'integer',
                'min:1'
            ],
            'duration' => [
                'required',
                'integer',
                'min:1'
            ],
            'features' => [
                'required',
                'array'
            ],
            'status' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Oops! The subscription name is required. Please provide a name.',
            'name.string' => 'The subscription name must be a text string. No numbers or special characters allowed.',
            'name.max' => 'The subscription name is too long! Please keep it under 150 characters.',
            // 'name.unique' => 'This subscription name is already exist. Please choose a different one.',

            'description.required' => 'The description is required. It seems like you forgotten.',
            'description.string' => "The full description should be text. Let's keep it readable!",

            'price.required' => "The price is missing! Please add a price for this subscription.",
            'price.numeric' => "The price should be a number. No letters or symbols, please!",
            'price.min' => "The price can't be negative. Let's keep it positive!",

            'duration.required' => "Hmm, the duration for the subscription is required and must be at least 1 days.",
            'duration.numeric' => "The duration should be a number. No surprises here!",
            'duration.min' => "Duration can't be negative. Please enter a value of 0 or higher.",

            'features.required' => "Hey there! Don't forget to add at least one feature for the subscription.",
            'features.array' => "The features field must be an array of features.",

            'status.boolean' => 'The status must be either "active" or "inactive".',
        ];
    }
}
