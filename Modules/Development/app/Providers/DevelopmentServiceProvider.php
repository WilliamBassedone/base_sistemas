<?php

namespace Modules\Development\Providers;

use App\Support\Modules\ModuleServiceProvider;

class DevelopmentServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'Development';

    protected string $moduleNameLower = 'development';
}
