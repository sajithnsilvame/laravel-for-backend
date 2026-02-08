<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->route('user'))
            ],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'mobile_number' => ['sometimes', 'string', 'max:20'],
            'profile_picture' => ['sometimes', 'string', 'max:255'],
            'role_id' => ['sometimes', 'exists:roles,id'],
        ];
    }
}
