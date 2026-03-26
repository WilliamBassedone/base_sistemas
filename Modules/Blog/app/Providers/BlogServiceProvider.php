<?php

namespace Modules\Blog\Providers;

use App\Support\Modules\ModuleServiceProvider;

class BlogServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'Blog';

    protected string $moduleNameLower = 'blog';
}
