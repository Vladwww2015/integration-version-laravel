<?php

namespace IntegrationHelper\IntegrationVersionLaravel\Providers;

use IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersion;
use IntegrationHelper\IntegrationVersionLaravel\Models\IntegrationVersionItem;
use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        IntegrationVersionItem::class,
        IntegrationVersion::class,
    ];
}
