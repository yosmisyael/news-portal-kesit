<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserManagementRequest extends FormRequest
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
        $method = $this->method();

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' =>  ['required', 'string', 'max:255', 'email'],
            'password' =>  ['required', 'string', 'max:100', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'username' => ['required', 'string', 'alpha_dash:ascii', 'max:100', 'unique:users,username'],
        ];

        if ($method === 'PUT') {
            $rules = [
                'password' => ['required', 'string', 'max:100', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'confirmation' => ['required', 'same:password'],
            ];
        }

        return $rules;
    }
}
