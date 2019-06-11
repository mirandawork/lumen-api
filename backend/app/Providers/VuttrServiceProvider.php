<?php

namespace App\Providers;

use App\Entities\Tag;
use App\Entities\Tool;
use App\Services\VuttrService;
use App\Services\VuttrServiceInterface;
use Illuminate\Support\ServiceProvider;

class VuttrServiceProvider extends ServiceProvider
{

    /**
     *
     */
    public function register(): void
    {
        $this->app->bind(VuttrServiceInterface::class, function ($app) {
            return new VuttrService(\EntityManager::getRepository(Tool::class), \EntityManager::getRepository(Tag::class));
        });
    }
}
