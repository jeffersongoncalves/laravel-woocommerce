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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
