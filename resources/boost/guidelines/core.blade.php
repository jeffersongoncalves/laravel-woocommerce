## Laravel WooCommerce

This package provides a wrapper around the [WooCommerce REST API v3](https://woocommerce.github.io/woocommerce-rest-api-docs/) for products, variations, orders, refunds, customers, coupons, webhooks, taxes, shipping and reports.

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require jeffersongoncalves/laravel-woocommerce
php artisan vendor:publish --tag="laravel-woocommerce-config"
</code-snippet>
@endverbatim

Set `WOOCOMMERCE_BASE_URL`, `WOOCOMMERCE_CONSUMER_KEY` and `WOOCOMMERCE_CONSUMER_SECRET` in `.env`. The key pair is generated under WooCommerce > Settings > Advanced > REST API and is sent as HTTP Basic credentials, which WooCommerce only accepts over HTTPS.

### Features

- **Products**: CRUD plus variations, categories and tags.
- **Orders**: CRUD plus order notes and refunds.
- **Customers** and **Coupons**: CRUD (customers are always deleted permanently, with an optional `reassign`).
- **Webhooks**, **tax rates**, **payment gateways** and **shipping zones**: read/manage helpers.
- **Reports**: `salesReport()`, `topSellersReport()`, `systemStatus()`.
- **Batch**: `batch('products', ['create' => [...], 'update' => [...], 'delete' => [...]])` for any collection with a `/batch` endpoint.

@verbatim
<code-snippet name="Create an order" lang="php">
use Jeffersongoncalves\Woocommerce\Facades\Woocommerce;

$response = Woocommerce::createOrder([
    'payment_method' => 'bacs',
    'billing' => ['first_name' => 'Jane', 'email' => 'jane@example.com'],
    'line_items' => [['product_id' => 99, 'quantity' => 2]],
]);

$orderId = $response->json('id');
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="Config example" lang="php">
// config/woocommerce.php
return [
    'base_url' => env('WOOCOMMERCE_BASE_URL'),
    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
    'namespace' => env('WOOCOMMERCE_NAMESPACE', 'wp-json/wc/v3'),
];
</code-snippet>
@endverbatim

### Best Practices

- Every method returns an `Illuminate\Http\Client\Response` — check `->successful()`/`->status()` before trusting `->json()`.
- `$filters` and `$attributes` arrays go straight to the REST API, so use the parameter names WooCommerce documents (`per_page`, `status`, `after`, `orderby`, ...) instead of looking for a dedicated method.
- Prefer `batch()` over a loop of single writes when syncing many products or orders — one request instead of hundreds.
- Money fields are strings in the WooCommerce API (`'regular_price' => '21.99'`); don't send floats.
- Use `Http::fake()` in tests instead of hitting a real store; the package resolves its HTTP client through Laravel's `Http` facade.
