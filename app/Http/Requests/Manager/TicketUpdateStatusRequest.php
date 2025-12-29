<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketUpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('manager') ?? false;
    }

    public function rules(): array
    {
        // лучше, чем "string": не даст записать левый статус
        $allowed = config('tickets.statuses', ['new','in_progress','done','rejected']);

        return [
            'status' => ['required', Rule::in($allowed)],
        ];
    }
}