<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'pagination' => 'array',
            'pagination.current' => 'integer|required',
            'pagination.pageSize' => 'integer|required',
            'order' => [
                'string',
                'nullable',
                Rule::in(['ascend', 'descend'])
            ],
            'field' => 'string|nullable',
        ];
    }
}
