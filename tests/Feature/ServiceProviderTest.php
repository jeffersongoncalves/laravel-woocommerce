<?php

use Jeffersongoncalves\Woocommerce\Facades\Woocommerce as WoocommerceFacade;
use Jeffersongoncalves\Woocommerce\Woocommerce;

it('registers the woocommerce singleton', function () {
    expect(app(Woocommerce::class))->toBeInstanceOf(Woocommerce::class);
    expect(app(Woocommerce::class))->toBe(app(Woocommerce::class));
});

it('resolves the facade to the woocommerce class', function () {
    expect(WoocommerceFacade::getFacadeRoot())->toBeInstanceOf(Woocommerce::class);
});

it('merges the package config', function () {
    expect(config('woocommerce.namespace'))->toBe('wp-json/wc/v3');
});
