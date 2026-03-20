<?php

namespace App\Domains\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorización por roles de usuario
        return true;
    }

    public function rules(): array
    {
        return [
            'document_number' => ['required', 'string', 'max:10', 'unique:customers,document_number'],
            'name' => ['required', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:35'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
