<?php

namespace App\Providers;

<<<<<<< HEAD
=======
use App\Repositories\AuthRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
>>>>>>> 2a85dbadae3410ad595aaac69ed606fa6335e014
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
<<<<<<< HEAD
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
=======
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class,
        );
    }

>>>>>>> 2a85dbadae3410ad595aaac69ed606fa6335e014
    public function boot(): void
    {
        //
    }
}
