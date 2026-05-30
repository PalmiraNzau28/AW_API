<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFotoPerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'foto_perfil' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'foto_perfil.required' => 'A foto de perfil é obrigatória.',
            'foto_perfil.image'    => 'O ficheiro deve ser uma imagem.',
            'foto_perfil.mimes'    => 'A imagem deve ser do tipo: jpeg, jpg, png ou webp.',
            'foto_perfil.max'      => 'A imagem não pode ter mais de 2MB.',
        ];
    }
}