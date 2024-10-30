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
    protected static $init = false;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if(static::$init !== false) return;
        static::$init = true;

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->initContext();
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
    private function initContext()
    {
        $this->app->bind(DateTimeInterface::class, DateTime::class);
        $this->app->bind(GetterParentItemCollectionInterface::class, GetterParentItemCollection::class);
        $this->app->bind(IntegrationVersionRepositoryInterface::class, IntegrationVersionRepository::class);
        $this->app->bind(IntegrationVersionItemRepositoryInterface::class, IntegrationVersionItemRepository::class);

        $this->app->bind(HashGeneratorInterface::class, HashGenerator::class);
        $this->app->bind(IntegrationVersionManagerInterface::class, IntegrationVersionManager::class);
        $this->app->bind(IntegrationVersionItemManagerInterface::class, IntegrationVersionItemManager::class);

        Context::getInstance()
            ->setDateTime(app(DateTimeInterface::class))
            ->setIntegrationVersionItemRepository(app(IntegrationVersionItemRepositoryInterface::class))
            ->setIntegrationVersionRepository(app(IntegrationVersionRepositoryInterface::class))
            ->setGetterParentItemCollection(app(GetterParentItemCollectionInterface::class))
            ->setIntegrationVersionManager(app(IntegrationVersionManagerInterface::class))
            ->setIntegrationVersionItemManager(app(IntegrationVersionItemManagerInterface::class))
            ->setHashGenerator(app(HashGeneratorInterface::class));
    }
}
