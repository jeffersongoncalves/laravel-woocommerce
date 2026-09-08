<?php

namespace Jeffersongoncalves\Woocommerce\Tests;

use Jeffersongoncalves\Woocommerce\WoocommerceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WoocommerceServiceProvider::class,
        ];
    }
}
