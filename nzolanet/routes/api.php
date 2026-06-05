<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BazeController;
use App\Http\Controllers\API\ComentarioController;
use App\Http\Controllers\API\NotificacaoController;
use App\Http\Controllers\API\UtilizadorController;
use App\Http\Controllers\API\PublicacaoController;
use App\Http\Controllers\API\SeguidorController;
use Illuminate\Support\Facades\Route;

// Rotas publicas
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Feed público — qualquer pessoa pode ver as publicações
Route::get('/publicacoes', [PublicacaoController::class, 'index']);
Route::get('/publicacoes/{id}', [PublicacaoController::class, 'show']);

// Rotas protegidas
Route::middleware('auth:api')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/perfil', [AuthController::class, 'updatePerfil']);
        Route::post('/foto-perfil', [AuthController::class, 'updateFotoPerfil']);
    });

    // Rotas de Comentarios
    Route::get('/publicacoes/{publicacao_id}/comentarios', [ComentarioController::class, 'index']);
    Route::post('/publicacoes/{publicacao_id}/comentarios', [ComentarioController::class, 'store']);
    Route::put('/comentarios/{id}', [ComentarioController::class, 'update']);
    Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy']);

    // Rotas de Publicacoes
    Route::post('/publicacoes', [PublicacaoController::class, 'store']);
    Route::put('/publicacoes/{id}', [PublicacaoController::class, 'update']);
    Route::post('/publicacoes/{id}', [PublicacaoController::class, 'update']);
    Route::delete('/publicacoes/{id}', [PublicacaoController::class, 'destroy']);
    Route::post('/publicacoes/{publicacao_id}/baze', [BazeController::class, 'toggle']);

    // Rotas de notificação
    Route::get('/utilizadores/pesquisa', [UtilizadorController::class, 'search']);
    Route::get('/notificacoes', [NotificacaoController::class, 'index']);
    Route::post('/notificacoes/{id}/ler', [NotificacaoController::class, 'marcarComoLida']);

    // Rotas de Seguidores
    Route::post('/utilizadores/{id}/seguir', [SeguidorController::class, 'seguir']);
    Route::delete('/utilizadores/{id}/seguir', [SeguidorController::class, 'deixarSeguir']);
    Route::get('/utilizadores/{id}/seguidores', [SeguidorController::class, 'seguidores']);
    Route::get('/utilizadores/{id}/seguindo', [SeguidorController::class, 'seguindo']);


});
