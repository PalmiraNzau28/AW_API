<?php

namespace App\Services;

use App\Models\Utilizador;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function enviarLinkRecuperacao(string $email): array
    {
        $status = Password::broker('utilizadores')->sendResetLink(
            ['email' => $email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            return [
                'sucesso'  => true,
                'mensagem' => 'Link de recuperação enviado para o teu email.',
            ];
        }

        return [
            'sucesso'  => false,
            'mensagem' => 'Não foi possível enviar o link de recuperação. Tenta novamente.',
        ];
    }

    public function resetPassword(string $token, string $email, string $password): array
    {
        $status = Password::broker('utilizadores')->reset(
            [
                'email'                 => $email,
                'password'              => $password,
                'password_confirmation' => $password,
                'token'                 => $token,
            ],
            function (Utilizador $utilizador, string $password) {
                $utilizador->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($utilizador));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return [
                'sucesso'  => true,
                'mensagem' => 'Senha redefinida com sucesso.',
            ];
        }

        return [
            'sucesso'  => false,
            'mensagem' => 'Token inválido ou expirado. Solicita um novo link de recuperação.',
        ];
    }
}