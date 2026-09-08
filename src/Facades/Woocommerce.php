<?php

namespace Jeffersongoncalves\Woocommerce\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\Woocommerce\Woocommerce
 */
class Woocommerce extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-woocommerce';
    }
}
