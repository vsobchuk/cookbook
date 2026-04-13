<?php

namespace App\Http\Requests\Auth;

use App\Data\UserLoginData;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function data(): UserLoginData
    {
        return new UserLoginData(array_merge($this->validated(), [
            'ip' => $this->ip(),
            'remember' => $this->boolean('remember'),
        ]));
    }
}
