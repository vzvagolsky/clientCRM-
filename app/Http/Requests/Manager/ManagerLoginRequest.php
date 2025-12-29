<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class ManagerLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // логин доступен всем, проверка роли после Auth::attempt()
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
