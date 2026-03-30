<?php

namespace Modules\User\Providers;

use App\Support\Modules\ModuleServiceProvider;

class UserServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'User';

    protected string $moduleNameLower = 'user';
}
