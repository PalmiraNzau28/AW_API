<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Route;

// Rotas publicas
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Rotas protegidas
Route::middleware('auth:api')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout',      [AuthController::class, 'logout']);
        Route::post('/refresh',     [AuthController::class, 'refresh']);
        Route::get('/me',           [AuthController::class, 'me']);
        Route::put('/perfil',       [AuthController::class, 'updatePerfil']);
        Route::post('/foto-perfil', [AuthController::class, 'updateFotoPerfil']);
    });

    // Rotas de Comentarios
    Route::get('/publicacoes/{publicacao_id}/comentarios',  [ComentarioController::class, 'index']);
    Route::post('/publicacoes/{publicacao_id}/comentarios', [ComentarioController::class, 'store']);
    Route::put('/comentarios/{id}',                         [ComentarioController::class, 'update']);
    Route::delete('/comentarios/{id}',                      [ComentarioController::class, 'destroy']);

});
