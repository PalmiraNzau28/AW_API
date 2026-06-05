<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'     => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:utilizadores,username',
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'email'    => [
                'required',
                'string',
                'email:rfc',
                'max:150',
                'unique:utilizadores,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'          => 'O nome é obrigatório.',
            'nome.min'               => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max'               => 'O nome não pode ter mais de 100 caracteres.',
            'username.required'      => 'O username é obrigatório.',
            'username.min'           => 'O username deve ter pelo menos 3 caracteres.',
            'username.max'           => 'O username não pode ter mais de 50 caracteres.',
            'username.unique'        => 'Este username já está em uso.',
            'username.regex'         => 'O username só pode conter letras, números e underscores.',
            'email.required'         => 'O email é obrigatório.',
            'email.email'            => 'Introduza um email válido.',
            'email.max'              => 'O email não pode ter mais de 150 caracteres.',
            'email.unique'           => 'Este email já está registado.',
            'password.required'      => 'A senha é obrigatória.',
            'password.min'           => 'A senha deve ter pelo menos 8 caracteres.',
            'password.regex'         => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula e um número.',
            'password.confirmed'     => 'As senhas não coincidem.',
        ];
    }
}
