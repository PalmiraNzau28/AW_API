<?php

namespace App\Http\Requests\Publicacao;

use Illuminate\Foundation\Http\FormRequest;

// O FormRequest faz duas coisas antes de o código do Controller correr:
//   1. authorize() — verifica se o utilizador tem permissão para fazer este pedido
//   2. rules()     — valida os dados recebidos (body, ficheiros, etc.)
// Se a validação falhar, o Laravel devolve automaticamente um erro 422
// com os campos inválidos — sem precisar de nenhum código extra no Controller.

class CreatePublicacaoRequest extends FormRequest
{
    // authorize() devolve true → qualquer utilizador autenticado pode criar publicações.
    // A autenticação em si é garantida pelo middleware 'auth:api' na rota,
    // por isso aqui apenas confirmamos que não há restrições adicionais.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'sometimes' significa: só valida este campo se ele vier na request.
            // 'nullable' permite que o valor seja explicitamente null.
            // A regra de negócio "pelo menos um dos três" é tratada com 'required_without_all'.
            'texto'  => [
                'nullable',
                'string',
                'max:500', // Máximo 500 caracteres conforme especificado
                // Obrigatório APENAS se imagem e vídeo estiverem ambos ausentes
                'required_without_all:imagem,video',
            ],
            'imagem' => [
                'nullable',
                'image',                     // Deve ser uma imagem válida
                'mimes:jpeg,jpg,png,webp',
                'max:5120',                  // 5120 KB = 5 MB
                'required_without_all:texto,video',
            ],
            'video'  => [
                'nullable',
                'file',                      // Qualquer ficheiro (não só imagem)
                'mimes:mp4,mov,avi,webm',
                'max:51200',                 // 51200 KB = 50 MB
                'required_without_all:texto,imagem',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'texto.max'                    => 'O texto não pode ter mais de 500 caracteres.',
            'texto.required_without_all'   => 'A publicação deve ter pelo menos texto, imagem ou vídeo.',
            'imagem.image'                 => 'O ficheiro de imagem não é válido.',
            'imagem.mimes'                 => 'A imagem deve ser do tipo: jpeg, jpg, png ou webp.',
            'imagem.max'                   => 'A imagem não pode ter mais de 5MB.',
            'imagem.required_without_all'  => 'A publicação deve ter pelo menos texto, imagem ou vídeo.',
            'video.file'                   => 'O ficheiro de vídeo não é válido.',
            'video.mimes'                  => 'O vídeo deve ser do tipo: mp4, mov, avi ou webm.',
            'video.max'                    => 'O vídeo não pode ter mais de 50MB.',
            'video.required_without_all'   => 'A publicação deve ter pelo menos texto, imagem ou vídeo.',
        ];
    }
}
