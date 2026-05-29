<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $utilizadorId = Auth::id();

        return [
            'nome'           => [
                'sometimes',
                'string',
                'min:3',
                'max:100',
            ],
            'username'       => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'unique:utilizadores,username,' . $utilizadorId,
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'bio'            => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'perfil_privado' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.min'               => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max'               => 'O nome não pode ter mais de 100 caracteres.',
            'username.min'           => 'O username deve ter pelo menos 3 caracteres.',
            'username.max'           => 'O username não pode ter mais de 50 caracteres.',
            'username.unique'        => 'Este username já está em uso.',
            'username.regex'         => 'O username só pode conter letras, números e underscores.',
            'bio.max'                => 'A bio não pode ter mais de 255 caracteres.',
            'perfil_privado.boolean' => 'O campo perfil privado deve ser verdadeiro ou falso.',
        ];
    }
}