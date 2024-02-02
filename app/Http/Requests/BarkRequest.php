<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'message' => [
                'string',
                'required',
                'min:4',
                'max:500',
                fn ($attribute, $value, $fail) => $this->validateFourLetterBark($attribute, $value, $fail),
            ]
        ];
    }

    /**
     * Define custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'message.required' => 'A bark is required.',
            'message.min' => 'A bark must be at least :min characters.',
            'message.max' => 'A bark cannot be more than 500 characters.',
        ];
    }

    /**
     * Custom validation for the 'message' attribute.
     */
    private function validateFourLetterBark(string $attribute, string $value, \Closure $fail): void
    {
        if (strlen($value) === 4 && strtolower($value) !== 'bark') {
            $fail('If a bark is 4 characters, it can only be the word "Bark".');
        }
    }
}
