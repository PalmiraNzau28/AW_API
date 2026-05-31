<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SeguidorController;
use Illuminate\Support\Facades\Route;

// ─── Rotas públicas ───────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password',  [AuthController::class, 'resetPassword']);
});

// ─── Rotas protegidas ─────────────────────────────────────────────────────
Route::middleware('auth:api')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout',      [AuthController::class, 'logout']);
        Route::post('/refresh',     [AuthController::class, 'refresh']);
        Route::get('/me',           [AuthController::class, 'me']);
        Route::put('/perfil',       [AuthController::class, 'updatePerfil']);
        Route::post('/foto-perfil', [AuthController::class, 'updateFotoPerfil']);
    });

    Route::prefix('utilizadores')->group(function () {
        Route::post('/{id}/seguir',         [SeguidorController::class, 'seguir']);
        Route::delete('/{id}/deixar-seguir',[SeguidorController::class, 'deixarSeguir']);
        Route::get('/{id}/seguidores',      [SeguidorController::class, 'seguidores']);
        Route::get('/{id}/seguindo',        [SeguidorController::class, 'seguindo']);
    });

});