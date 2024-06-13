<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class FrontendUserRequest extends FormRequest
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
        if ($this->method() === "POST") {
            return [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'mobile' => 'required|string|max:255|unique:frontend_users',
                'email' => 'required|string|email|max:255|unique:frontend_users',
                'password' => ['required', 'string', 'min:8', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            ];
        } else {

            $frontendUser = $this->route('frontend_user');

            return [
                'fname' => 'sometimes|required|string|max:255',
                'lname' => 'sometimes|required|string|max:255',
                'mobile' => 'sometimes|required|string|max:255|unique:frontend_users,mobile,' . $frontendUser->id,
                'email' => 'sometimes|required|string|email|max:255|unique:frontend_users,email,' . $frontendUser->id,
                'password' => ['sometimes', 'required', 'string', 'min:8', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            ];
        }
    }
}
