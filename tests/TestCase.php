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

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('woocommerce.base_url', 'https://example.com');
        $app['config']->set('woocommerce.consumer_key', 'ck_fake');
        $app['config']->set('woocommerce.consumer_secret', 'cs_fake');
        $app['config']->set('woocommerce.namespace', 'wp-json/wc/v3');
    }
}
