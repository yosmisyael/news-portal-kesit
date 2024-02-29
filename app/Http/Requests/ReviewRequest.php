<?php

namespace App\Http\Requests;

use App\Enums\PostStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
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
        $status = PostStatusEnum::cases();

        $method = $this->method();

        $rules = [
            'messages' => ['required', 'string'],
            'status' => ['required', Rule::in($status)],
        ];

        if ($method === 'POST') {
            $rules['submissionId'] =  ['required', 'uuid', 'exists:submissions,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'messages.required' => 'Messages should be attached.',
            'status.in' => 'Please select a valid status.'
        ];
    }
}
