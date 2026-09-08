---
name: laravel-woocommerce-development
description: Build and work with the Laravel WooCommerce REST API client, including products, variations, orders, refunds, customers, coupons, webhooks, taxes, shipping and reports.
---

# Laravel WooCommerce Development

## When to use this skill

Use this skill when:
- Calling the WooCommerce REST API from a Laravel app (products, orders, customers, coupons, webhooks, reports)
- Configuring the `WOOCOMMERCE_BASE_URL` / `WOOCOMMERCE_CONSUMER_KEY` / `WOOCOMMERCE_CONSUMER_SECRET` environment variables
- Writing tests that mock WooCommerce HTTP calls

## Core Concepts

### The `Woocommerce` class

`Jeffersongoncalves\Woocommerce\Woocommerce` is bound as a singleton in the container, built from `config('woocommerce.*')`. Every public method sends a request via Laravel's `Http` facade (HTTP Basic authenticated with the consumer key/secret) and returns an `Illuminate\Http\Client\Response`.

The client base URL is `{base_url}/{namespace}` — by default `https://example.com/wp-json/wc/v3` — with trailing slashes normalised, so method paths are plain (`/products`, `/orders/727`).

```php
use Jeffersongoncalves\Woocommerce\Facades\Woocommerce;

$response = Woocommerce::getOrder(727);

if ($response->successful()) {
    $order = $response->json();
}
```

### Resource coverage

| Resource            | Methods |
|---------------------|---------|
| Products            | `listProducts`, `getProduct`, `createProduct`, `updateProduct`, `deleteProduct` |
| Variations          | `listVariations`, `getVariation`, `createVariation`, `updateVariation`, `deleteVariation` |
| Product categories  | `listProductCategories`, `getProductCategory`, `createProductCategory`, `updateProductCategory`, `deleteProductCategory` |
| Product tags        | `listProductTags`, `getProductTag`, `createProductTag`, `updateProductTag`, `deleteProductTag` |
| Orders              | `listOrders`, `getOrder`, `createOrder`, `updateOrder`, `deleteOrder` |
| Order notes         | `listOrderNotes`, `getOrderNote`, `createOrderNote`, `deleteOrderNote` |
| Refunds             | `listRefunds`, `getRefund`, `createRefund`, `deleteRefund` |
| Customers           | `listCustomers`, `getCustomer`, `createCustomer`, `updateCustomer`, `deleteCustomer` |
| Coupons             | `listCoupons`, `getCoupon`, `createCoupon`, `updateCoupon`, `deleteCoupon` |
| Webhooks            | `listWebhooks`, `getWebhook`, `createWebhook`, `updateWebhook`, `deleteWebhook` |
| Tax rates           | `listTaxRates`, `getTaxRate`, `createTaxRate`, `updateTaxRate`, `deleteTaxRate` |
| Payment gateways    | `listPaymentGateways`, `getPaymentGateway`, `updatePaymentGateway` |
| Shipping            | `listShippingZones`, `getShippingZone`, `listShippingZoneMethods` |
| Reports             | `salesReport`, `topSellersReport`, `systemStatus` |
| Batch               | `batch` |

## Common Patterns

### Filtering collections

```php
Woocommerce::listOrders([
    'status' => 'processing',
    'per_page' => 100,
    'after' => '2026-01-01T00:00:00',
    'orderby' => 'date',
    'order' => 'desc',
]);
```

WooCommerce caps `per_page` at 100 and returns `X-WP-Total` / `X-WP-TotalPages` headers, so paginate with `page`:

```php
$page = 1;

do {
    $response = Woocommerce::listProducts(['per_page' => 100, 'page' => $page]);
    // ... handle $response->json()
    $page++;
} while ($page <= (int) $response->header('X-WP-TotalPages'));
```

### Trash vs. permanent delete

`deleteProduct`, `deleteOrder` and `deleteCoupon` trash by default; pass `force: true` to delete permanently. Variations, terms, notes, refunds, webhooks and tax rates have no trash, so those methods always force. `deleteCustomer` always forces and takes an optional `reassign` user id for their content.

### Batch writes

```php
Woocommerce::batch('products', [
    'create' => [['name' => 'Mug', 'regular_price' => '9.90']],
    'update' => [['id' => 99, 'regular_price' => '18.90']],
    'delete' => [12],
]);
```

WooCommerce limits a batch to 100 objects per request by default.

### Testing with `Http::fake()`

```php
use Illuminate\Support\Facades\Http;
use Jeffersongoncalves\Woocommerce\Facades\Woocommerce;

Http::fake(['example.com/wp-json/wc/v3/orders/*' => Http::response(['id' => 727])]);

$response = Woocommerce::getOrder(727);

Http::assertSent(fn ($request) => $request->url() === 'https://example.com/wp-json/wc/v3/orders/727');
```

## Troubleshooting

### Error: 401 `woocommerce_rest_cannot_view`

**Causa**: wrong key pair, a Read-only key used for a write, or a host that strips the `Authorization` header.

**Solução**: confirm the key permissions in WooCommerce > Settings > Advanced > REST API. If the credentials are right but requests still fail, the site is likely dropping `Authorization` (common with Apache + CGI); add `SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1` to `.htaccess`.

### Error: 404 `woocommerce_rest_no_route`

**Causa**: WooCommerce not active, permalinks set to "Plain", or a wrong `WOOCOMMERCE_NAMESPACE`.

**Solução**: set pretty permalinks and check `config('woocommerce.namespace')` matches the installed API version (`wp-json/wc/v3`).

### Basic auth ignored over HTTP

**Causa**: WooCommerce only accepts key/secret as Basic credentials over HTTPS.

**Solução**: point `WOOCOMMERCE_BASE_URL` at an HTTPS URL.

## API Reference

### `Woocommerce::createProduct(array $attributes)`

| Parameter        | Type     | Description                                          |
|------------------|----------|------------------------------------------------------|
| `name`           | `string` | Product name                                         |
| `type`           | `string` | `simple`, `grouped`, `external`, `variable`          |
| `regular_price`  | `string` | Price as a string, not a float                       |
| `status`         | `string` | `draft`, `pending`, `private`, `publish`             |
| `categories`     | `array`  | `[['id' => 9]]`                                      |
| `manage_stock`   | `bool`   | Enable stock management                              |

**Returns**: `Illuminate\Http\Client\Response`
