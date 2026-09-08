<div class="filament-hidden">

![Laravel WooCommerce](https://raw.githubusercontent.com/jeffersongoncalves/laravel-woocommerce/main/art/jeffersongoncalves-laravel-woocommerce.png)

</div>

# Laravel WooCommerce

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-woocommerce.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-woocommerce)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-woocommerce/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-woocommerce/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-woocommerce/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-woocommerce/actions?query=workflow%3A%22Fix+PHP+code+styling%22+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-woocommerce.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-woocommerce)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-woocommerce.svg?style=flat-square)](LICENSE.md)

WooCommerce REST API integration for Laravel. A thin wrapper around the [WooCommerce REST API v3](https://woocommerce.github.io/woocommerce-rest-api-docs/) covering products, variations, orders, refunds, customers, coupons, webhooks, taxes, shipping and reports, built on Laravel's HTTP client.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-woocommerce
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-woocommerce-config"
```

This is the contents of the published config file:

```php
return [
    'base_url' => env('WOOCOMMERCE_BASE_URL'),
    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
    'namespace' => env('WOOCOMMERCE_NAMESPACE', 'wp-json/wc/v3'),
];
```

Generate a key pair in WooCommerce under **WooCommerce > Settings > Advanced > REST API**, then add your credentials to `.env`:

```
WOOCOMMERCE_BASE_URL=https://example.com
WOOCOMMERCE_CONSUMER_KEY=ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
WOOCOMMERCE_CONSUMER_SECRET=cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

The keys are sent as HTTP Basic credentials, which WooCommerce only accepts over HTTPS. Give the key pair **Read** access for reporting and **Read/Write** for anything that mutates the store.

## Usage

You can use the `Woocommerce` facade, or inject `Jeffersongoncalves\Woocommerce\Woocommerce` wherever you need it.

### Products

```php
use Jeffersongoncalves\Woocommerce\Facades\Woocommerce;

Woocommerce::listProducts(['per_page' => 20, 'status' => 'publish', 'search' => 'shirt']);
Woocommerce::getProduct(99);

Woocommerce::createProduct([
    'name' => 'T-Shirt',
    'type' => 'simple',
    'regular_price' => '21.99',
    'categories' => [['id' => 9]],
]);

Woocommerce::updateProduct(99, ['regular_price' => '19.99']);

Woocommerce::deleteProduct(99);              // moves to trash
Woocommerce::deleteProduct(99, force: true); // deletes permanently
```

### Variations

```php
Woocommerce::listVariations(99, ['per_page' => 20]);
Woocommerce::getVariation(99, 7);
Woocommerce::createVariation(99, ['regular_price' => '9.99', 'attributes' => [['id' => 1, 'option' => 'Blue']]]);
Woocommerce::updateVariation(99, 7, ['stock_quantity' => 10]);
Woocommerce::deleteVariation(99, 7);
```

### Product categories and tags

```php
Woocommerce::listProductCategories();
Woocommerce::getProductCategory(9);
Woocommerce::createProductCategory(['name' => 'Hoodies', 'slug' => 'hoodies']);
Woocommerce::updateProductCategory(9, ['description' => 'Warm stuff']);
Woocommerce::deleteProductCategory(9);

Woocommerce::listProductTags();
Woocommerce::getProductTag(4);
Woocommerce::createProductTag(['name' => 'Summer']);
Woocommerce::updateProductTag(4, ['slug' => 'summer']);
Woocommerce::deleteProductTag(4);
```

### Orders

```php
Woocommerce::listOrders(['status' => 'processing', 'per_page' => 50, 'after' => '2026-01-01T00:00:00']);
Woocommerce::getOrder(727);

Woocommerce::createOrder([
    'payment_method' => 'bacs',
    'billing' => ['first_name' => 'Jane', 'email' => 'jane@example.com'],
    'line_items' => [['product_id' => 99, 'quantity' => 2]],
]);

Woocommerce::updateOrder(727, ['status' => 'completed']);
Woocommerce::deleteOrder(727);
```

### Order notes and refunds

```php
Woocommerce::listOrderNotes(727);
Woocommerce::getOrderNote(727, 5);
Woocommerce::createOrderNote(727, 'Shipped today', customerNote: true);
Woocommerce::deleteOrderNote(727, 5);

Woocommerce::listRefunds(727);
Woocommerce::getRefund(727, 8);
Woocommerce::createRefund(727, ['amount' => '10.00', 'reason' => 'Damaged']);
Woocommerce::deleteRefund(727, 8);
```

### Customers

```php
Woocommerce::listCustomers(['per_page' => 25, 'role' => 'customer']);
Woocommerce::getCustomer(25);
Woocommerce::createCustomer(['email' => 'jane@example.com', 'first_name' => 'Jane']);
Woocommerce::updateCustomer(25, ['last_name' => 'Doe']);
Woocommerce::deleteCustomer(25, reassign: 1); // WooCommerce always deletes customers permanently
```

### Coupons

```php
Woocommerce::listCoupons();
Woocommerce::getCoupon(3);
Woocommerce::createCoupon(['code' => 'BLACKFRIDAY', 'discount_type' => 'percent', 'amount' => '20']);
Woocommerce::updateCoupon(3, ['amount' => '25']);
Woocommerce::deleteCoupon(3, force: true);
```

### Webhooks

```php
Woocommerce::listWebhooks();
Woocommerce::getWebhook(2);
Woocommerce::createWebhook('order.created', 'https://example.test/hooks/woo', ['status' => 'active']);
Woocommerce::updateWebhook(2, ['status' => 'paused']);
Woocommerce::deleteWebhook(2);
```

### Taxes, payment gateways and shipping

```php
Woocommerce::listTaxRates(['class' => 'standard']);
Woocommerce::getTaxRate(1);
Woocommerce::createTaxRate(['country' => 'BR', 'rate' => '17.0000', 'name' => 'ICMS']);
Woocommerce::updateTaxRate(1, ['rate' => '18.0000']);
Woocommerce::deleteTaxRate(1);

Woocommerce::listPaymentGateways();
Woocommerce::getPaymentGateway('bacs');
Woocommerce::updatePaymentGateway('bacs', ['enabled' => true]);

Woocommerce::listShippingZones();
Woocommerce::getShippingZone(3);
Woocommerce::listShippingZoneMethods(3);
```

### Reports and system status

```php
Woocommerce::salesReport(['period' => 'week']);
Woocommerce::topSellersReport(['period' => 'month']);
Woocommerce::systemStatus();
```

### Batch operations

Any collection that supports `POST /<resource>/batch` can be driven through `batch()`:

```php
Woocommerce::batch('products', [
    'create' => [['name' => 'Mug', 'regular_price' => '9.90']],
    'update' => [['id' => 99, 'regular_price' => '18.90']],
    'delete' => [12],
]);
```

Every method returns an `Illuminate\Http\Client\Response`, so you can use `->json()`, `->successful()`, `->status()`, etc. as usual. `$filters` and `$attributes` arrays are passed straight through to the REST API, so any parameter documented by WooCommerce is supported.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Simão Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
