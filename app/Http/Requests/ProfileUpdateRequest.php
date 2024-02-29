<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

/**
 * @property mixed $name
 * @property mixed $username
 */
class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => Str::lower($this->username),
            'name' => ucwords($this->name),
        ]);

        $this->mergeIfMissing([
            'id' => auth()->id(),
        ]);
    }

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = auth()->user();
        $method = $this->method();

        if ($this->url() === route('user.profile.updateProfile', ['username' => '@' . $user->username])) {
            return [
                'profile' => ['required', File::image()->max('500kb')],
            ];
        }

        if ($method === 'PATCH') {
            return [
                'id' => ['required', 'uuid'],
                'password' => ['required', 'string', 'max:100', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'confirmation' => ['required', 'same:password'],

            ];
        }

        return [
            'id' => ['required', 'uuid'],
            'username' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ];
    }
}
