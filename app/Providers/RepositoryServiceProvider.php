<?php

namespace App\Providers;

use App\Repositories\Contracts\LinkRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquents\LinkRepository;
use App\Repositories\Eloquents\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LinkRepositoryInterface::class, LinkRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
