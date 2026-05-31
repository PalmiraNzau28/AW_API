<?php

namespace App\Http\Requests\Comentario;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComentarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'texto' => 'required|string|min:1|max:300',
        ];
    }

    public function messages(): array
    {
        return [
            'texto.required' => 'O texto do comentário é obrigatório.',
            'texto.min'      => 'O comentário deve ter pelo menos 1 caracter.',
            'texto.max'      => 'O comentário não pode ter mais de 300 caracteres.',
        ];
    }
}