<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserLoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'email' => 'required|email|',
            'password' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            // API request, return JSON response with custom messages
            throw new ValidationException(
                $validator,
                new JsonResponse([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422)
            );
        } else {
            // Web request, use the default behavior
            parent::failedValidation($validator);
        }
    }

    public function messages()
    {
        return [];
    }
}
