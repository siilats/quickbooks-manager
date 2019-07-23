<?php

namespace Hotrush\QuickBooksManager\Tests;

use Hotrush\QuickBooksManager\QuickBooksManagerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [QuickBooksManagerServiceProvider::class];
    }
}