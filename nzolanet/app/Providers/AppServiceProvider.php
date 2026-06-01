<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\ComentarioRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\PublicacaoRepositoryInterface;
use App\Repositories\Interfaces\PublicacaoRepository;
use App\Repositories\Interfaces\ComentarioRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class,
        );

        $this->app->bind(
            PublicacaoRepositoryInterface::class,
            PublicacaoRepository::class,
        );

        $this->app->bind(
            ComentarioRepositoryInterface::class,
            ComentarioRepository::class,
        );
    }

    public function boot(): void
    {
        //
    }
}