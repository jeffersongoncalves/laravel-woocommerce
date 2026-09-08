<?php

namespace Jeffersongoncalves\Woocommerce;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WoocommerceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-woocommerce')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Woocommerce::class, fn () => new Woocommerce(
            baseUrl: (string) config('woocommerce.base_url'),
            consumerKey: (string) config('woocommerce.consumer_key'),
            consumerSecret: (string) config('woocommerce.consumer_secret'),
            namespace: (string) config('woocommerce.namespace'),
        ));
    }
}
