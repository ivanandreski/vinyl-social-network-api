<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\AlbumCacheRepository;
use App\Repository\Interface\AlbumCacheRepositoryInterface;

class AlbumCacheRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AlbumCacheRepositoryInterface::class, AlbumCacheRepository::class);
    }
}
