<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'category' => [],
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        $id = $this->route('id');

        $rules = [
            'content' => ['required', 'string'],
            'category' => ['array'],
            'category.*' => ['exists:categories,id'],
        ];

        if ($method === 'POST') {
            $rules['title'] = ['required', 'string', 'unique:posts,title'];
        }

        if ($method === 'PUT') {
            $rules['title'] = ['required', 'string', Rule::unique('posts', 'title')->ignore($id)];
        }

        return $rules;
    }
}
