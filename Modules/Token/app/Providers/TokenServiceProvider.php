<?php

namespace Modules\Token\Providers;

use App\Support\Modules\ModuleServiceProvider;

class TokenServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'Token';

    protected string $moduleNameLower = 'token';
}
