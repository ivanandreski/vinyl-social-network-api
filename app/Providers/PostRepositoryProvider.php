<?php

namespace App\Providers;

use App\Repository\PostRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\Interface\PostRepositoryInterface;

class PostRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
    }
}
