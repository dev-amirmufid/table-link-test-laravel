<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiUserRequest extends FormRequest
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
        $userId = $this->route('id');
        $isCreate = !$this->route('id');

        return [
            'name' => $isCreate ? 'required|string|max:255' : 'sometimes|string|max:255',
            'email' => [
                $isCreate ? 'required' : 'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => $isCreate
                ? 'required|string|min:8'
                : 'sometimes|string|min:8',
            'role' => $isCreate ? 'required|in:admin,user' : 'sometimes|in:admin,user',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name may not be greater than 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email has already been taken',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'role.required' => 'Role is required',
            'role.in' => 'Role must be either admin or user',
        ];
    }
}
