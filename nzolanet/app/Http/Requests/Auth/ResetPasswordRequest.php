<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'    => [
                'required',
                'string',
            ],
            'email'    => [
                'required',
                'string',
                'email:rfc',
                'exists:utilizadores,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required'     => 'O token é obrigatório.',
            'email.required'     => 'O email é obrigatório.',
            'email.email'        => 'Introduza um email válido.',
            'email.exists'       => 'Não existe nenhuma conta com este email.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.regex'     => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula e um número.',
        ];
    }
}
