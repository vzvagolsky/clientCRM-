<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Виджет публичный, регистрация клиента не нужна
        return true;
    }

    public function rules(): array
    {
        return [
            // Customer fields
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],

            // Ticket fields
            'subject' => ['required', 'string', 'min:3', 'max:255'],
            'message' => ['required', 'string', 'min:5', 'max:5000'],

            // Attachments (если виджет отправляет файлы)
            'attachments' => ['sometimes', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:120000'], // 5MB
        ];
    }

   protected function prepareForValidation(): void
{
    $this->merge([
        'email' => is_string($this->email) ? trim($this->email) : $this->email,
        'name'  => is_string($this->name) ? trim($this->name) : $this->name,
        'phone' => is_string($this->phone) ? trim($this->phone) : $this->phone,
    ]);
}

   
    public function messages(): array
    {
        return [
            'phone.regex' => 'Телефон должен быть в формате E.164 (например, +491761234567).',
        ];
    }
}