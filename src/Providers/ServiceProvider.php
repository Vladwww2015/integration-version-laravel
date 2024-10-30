<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Providers;

use IntegrationHelper\IntegrationVersion\Context;
use IntegrationHelper\IntegrationVersion\DateTimeInterface;
use IntegrationHelper\IntegrationVersion\GetterParentItemCollectionInterface;
use IntegrationHelper\IntegrationVersion\HashGenerator;
use IntegrationHelper\IntegrationVersion\HashGeneratorInterface;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManager;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManagerInterface;
use IntegrationHelper\IntegrationVersion\IntegrationVersionManager;
use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionItemRepositoryInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionRepositoryInterface;
use IntegrationHelper\IntegrationVersionLaravel\DateTime;
use IntegrationHelper\IntegrationVersionLaravel\GetterParentItemCollection;
use IntegrationHelper\IntegrationVersionLaravel\Repositories\IntegrationVersionItemRepository;
use IntegrationHelper\IntegrationVersionLaravel\Repositories\IntegrationVersionRepository;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app instanceof \Illuminate\Foundation\Application) {
            $self = $this;
            $this->app->booted(function ($app) use($self) {
                $self->initContext($app);
            });
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * @return void
     */
    private function initContext(\Illuminate\Foundation\Application $app)
    {
        $app->instance(DateTimeInterface::class, $app->make(DateTime::class));
        $app->instance(GetterParentItemCollectionInterface::class, $app->make(GetterParentItemCollection::class));
        $app->instance(IntegrationVersionRepositoryInterface::class, $app->make(IntegrationVersionRepository::class));
        $app->instance(IntegrationVersionItemRepositoryInterface::class, $app->make(IntegrationVersionItemRepository::class));

        $app->instance(HashGeneratorInterface::class, $app->make(HashGenerator::class));
        $app->instance(IntegrationVersionManagerInterface::class, $app->make(IntegrationVersionManager::class));
        $app->instance(IntegrationVersionItemManagerInterface::class, $app->make(IntegrationVersionItemManager::class));

        Context::getInstance()
            ->setDateTime($app->make(DateTimeInterface::class))
            ->setIntegrationVersionItemRepository($app->make(IntegrationVersionItemRepositoryInterface::class))
            ->setIntegrationVersionRepository($app->make(IntegrationVersionRepositoryInterface::class))
            ->setGetterParentItemCollection($app->make(GetterParentItemCollectionInterface::class))
            ->setIntegrationVersionManager($app->make(IntegrationVersionManagerInterface::class))
            ->setIntegrationVersionItemManager($app->make(IntegrationVersionItemManagerInterface::class))
            ->setHashGenerator($app->make(HashGeneratorInterface::class));
    }
}
