<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class TicketIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('manager') ?? false;
    }

    public function rules(): array
    {
        return [
            'from'   => ['nullable', 'date_format:Y-m-d'],
            'to'     => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'status' => ['nullable', 'string', 'max:32'],
            'email'  => ['nullable', 'string', 'max:255'],
            'phone'  => ['nullable', 'string', 'regex:/^\+[1-9]\d{1,14}$/'], // E.164
            'page'   => ['nullable', 'integer', 'min:1'],
        ];
    }
	
	
	public function messages(): array
    {
        return [
            'phone.regex' => 'Телефон должен быть в формате E.164 (например, +491761234567).',
        ];
    }


    public function validatedFilters(): array
    {
        return $this->validated();
    }
}