<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeadlineRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', 'unique:headlines,title'],
        ];

        if ($method === 'PUT') {
            $id = $this->route('id');
            $rules['title'] = ['required', 'string', 'max:255', Rule::unique('headlines', 'title')->ignore($id)];
        }

        return $rules;
    }
}
