<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'userId' => ['required', 'exists:users,id'],
            'categoryId' => ['required', 'exists:categories,id'],
            'cityId' => ['required', 'exists:cities,id'],
            'provinceId' => ['required', 'exists:provinces,id'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
        ];
    }
}
