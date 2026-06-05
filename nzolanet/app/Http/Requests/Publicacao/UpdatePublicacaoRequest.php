<?php

namespace App\Http\Requests\Publicacao;

use Illuminate\Foundation\Http\FormRequest;

// Na atualização, NENHUM campo é obrigatório individualmente —
// o utilizador pode enviar só o texto, só a imagem, etc.
// MAS a request inteira não pode vir completamente vazia:
// deve ter pelo menos um campo com valor.
// Essa verificação é feita no Service, após montar o DTO.

class UpdatePublicacaoRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'texto' => [
        'sometimes',
        'nullable',
        'string',
        'max:500',
      ],
      'imagem' => [
        'sometimes',
        'nullable',
        'image',
        'mimes:jpeg,jpg,png,webp',
        'max:5120', // 5 MB
      ],
      'video' => [
        'sometimes',
        'nullable',
        'file',
        'mimes:mp4,mov,avi,webm',
        'max:51200', // 50 MB
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'texto.max' => 'O texto não pode ter mais de 500 caracteres.',
      'imagem.image' => 'O ficheiro de imagem não é válido.',
      'imagem.mimes' => 'A imagem deve ser do tipo: jpeg, jpg, png ou webp.',
      'imagem.max' => 'A imagem não pode ter mais de 5MB.',
      'video.file' => 'O ficheiro de vídeo não é válido.',
      'video.mimes' => 'O vídeo deve ser do tipo: mp4, mov, avi ou webm.',
      'video.max' => 'O vídeo não pode ter mais de 50MB.',
    ];
  }
}
