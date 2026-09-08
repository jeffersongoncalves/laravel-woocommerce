<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Jeffersongoncalves\Woocommerce\Facades\Woocommerce;

beforeEach(function () {
    Http::preventStrayRequests();
});

it('lists products with filters and basic auth', function () {
    Http::fake(['example.com/wp-json/wc/v3/products*' => Http::response([])]);

    $response = Woocommerce::listProducts(['per_page' => 20, 'status' => 'publish']);

    expect($response->successful())->toBeTrue();
    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wc/v3/products')
        && $request['per_page'] === 20
        && $request['status'] === 'publish'
        && $request->hasHeader('Authorization', 'Basic '.base64_encode('ck_fake:cs_fake')));
});

it('gets a product', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/99' => Http::response(['id' => 99])]);

    expect(Woocommerce::getProduct(99)->json('id'))->toBe(99);
});

it('creates a product', function () {
    Http::fake(['example.com/wp-json/wc/v3/products' => Http::response(['id' => 1], 201)]);

    $response = Woocommerce::createProduct([
        'name' => 'T-Shirt',
        'type' => 'simple',
        'regular_price' => '21.99',
    ]);

    expect($response->status())->toBe(201);
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wc/v3/products'
        && $request['name'] === 'T-Shirt'
        && $request['regular_price'] === '21.99');
});

it('updates a product', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/99' => Http::response(['id' => 99])]);

    Woocommerce::updateProduct(99, ['regular_price' => '19.99']);

    Http::assertSent(fn (Request $request) => $request->method() === 'PUT'
        && $request->url() === 'https://example.com/wp-json/wc/v3/products/99'
        && $request['regular_price'] === '19.99');
});

it('deletes a product, trashing it by default', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/99*' => Http::response([])]);

    Woocommerce::deleteProduct(99);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wc/v3/products/99')
        && $request['force'] === false);
});

it('manages product variations', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/99/variations*' => Http::response([])]);

    Woocommerce::listVariations(99, ['per_page' => 10]);
    Woocommerce::createVariation(99, ['regular_price' => '9.99']);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wc/v3/products/99/variations')
        && $request['per_page'] === 10);
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['regular_price'] === '9.99');
});

it('deletes a variation with force by default', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/99/variations/7*' => Http::response([])]);

    Woocommerce::deleteVariation(99, 7);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
        && $request['force'] === true);
});

it('lists and creates product categories', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/categories*' => Http::response([])]);

    Woocommerce::listProductCategories();
    Woocommerce::createProductCategory(['name' => 'Hoodies', 'slug' => 'hoodies']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wc/v3/products/categories'
        && $request['name'] === 'Hoodies');
});

it('deletes a product tag with force', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/tags/4*' => Http::response([])]);

    Woocommerce::deleteProductTag(4);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
        && $request['force'] === true);
});

it('lists orders', function () {
    Http::fake(['example.com/wp-json/wc/v3/orders*' => Http::response([])]);

    Woocommerce::listOrders(['status' => 'processing', 'per_page' => 50]);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wc/v3/orders')
        && $request['status'] === 'processing');
});

it('creates an order', function () {
    Http::fake(['example.com/wp-json/wc/v3/orders' => Http::response(['id' => 727], 201)]);

    $response = Woocommerce::createOrder([
        'payment_method' => 'bacs',
        'line_items' => [['product_id' => 99, 'quantity' => 2]],
    ]);

    expect($response->json('id'))->toBe(727);
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['payment_method'] === 'bacs'
        && $request['line_items'][0]['quantity'] === 2);
});

it('creates an order note', function () {
    Http::fake(['example.com/wp-json/wc/v3/orders/727/notes' => Http::response(['id' => 5], 201)]);

    Woocommerce::createOrderNote(727, 'Shipped today', customerNote: true);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wc/v3/orders/727/notes'
        && $request['note'] === 'Shipped today'
        && $request['customer_note'] === true);
});

it('creates a refund', function () {
    Http::fake(['example.com/wp-json/wc/v3/orders/727/refunds' => Http::response(['id' => 8], 201)]);

    Woocommerce::createRefund(727, ['amount' => '10.00', 'reason' => 'Damaged']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wc/v3/orders/727/refunds'
        && $request['amount'] === '10.00');
});

it('deletes a customer with force and an optional reassignment', function () {
    Http::fake(['example.com/wp-json/wc/v3/customers/25*' => Http::response([])]);

    Woocommerce::deleteCustomer(25, reassign: 1);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
        && $request['force'] === true
        && $request['reassign'] === 1);
});

it('deletes a customer without a reassignment', function () {
    Http::fake(['example.com/wp-json/wc/v3/customers/25*' => Http::response([])]);

    Woocommerce::deleteCustomer(25);

    Http::assertSent(fn (Request $request) => $request['force'] === true
        && ! array_key_exists('reassign', $request->data()));
});

it('creates a coupon', function () {
    Http::fake(['example.com/wp-json/wc/v3/coupons' => Http::response(['id' => 3], 201)]);

    Woocommerce::createCoupon(['code' => 'BLACKFRIDAY', 'discount_type' => 'percent', 'amount' => '20']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['code'] === 'BLACKFRIDAY');
});

it('creates a webhook', function () {
    Http::fake(['example.com/wp-json/wc/v3/webhooks' => Http::response(['id' => 2], 201)]);

    Woocommerce::createWebhook('order.created', 'https://example.test/hook', ['status' => 'active']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['topic'] === 'order.created'
        && $request['delivery_url'] === 'https://example.test/hook'
        && $request['status'] === 'active');
});

it('lists tax rates', function () {
    Http::fake(['example.com/wp-json/wc/v3/taxes*' => Http::response([])]);

    Woocommerce::listTaxRates(['class' => 'standard']);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wc/v3/taxes')
        && $request['class'] === 'standard');
});

it('reads and updates payment gateways', function () {
    Http::fake(['example.com/wp-json/wc/v3/payment_gateways*' => Http::response([])]);

    Woocommerce::listPaymentGateways();
    Woocommerce::updatePaymentGateway('bacs', ['enabled' => true]);

    Http::assertSent(fn (Request $request) => $request->method() === 'PUT'
        && $request->url() === 'https://example.com/wp-json/wc/v3/payment_gateways/bacs'
        && $request['enabled'] === true);
});

it('reads shipping zones and their methods', function () {
    Http::fake(['example.com/wp-json/wc/v3/shipping/zones*' => Http::response([])]);

    Woocommerce::listShippingZones();
    Woocommerce::listShippingZoneMethods(3);

    Http::assertSent(fn (Request $request) => $request->url() === 'https://example.com/wp-json/wc/v3/shipping/zones');
    Http::assertSent(fn (Request $request) => $request->url() === 'https://example.com/wp-json/wc/v3/shipping/zones/3/methods');
});

it('reads reports and system status', function () {
    Http::fake([
        'example.com/wp-json/wc/v3/reports/*' => Http::response([]),
        'example.com/wp-json/wc/v3/system_status' => Http::response([]),
    ]);

    Woocommerce::salesReport(['period' => 'week']);
    Woocommerce::topSellersReport();
    Woocommerce::systemStatus();

    Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://example.com/wp-json/wc/v3/reports/sales')
        && $request['period'] === 'week');
    Http::assertSent(fn (Request $request) => $request->url() === 'https://example.com/wp-json/wc/v3/system_status');
});

it('sends a batch payload', function () {
    Http::fake(['example.com/wp-json/wc/v3/products/batch' => Http::response([])]);

    Woocommerce::batch('products', [
        'create' => [['name' => 'Mug']],
        'delete' => [12],
    ]);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wc/v3/products/batch'
        && $request['create'][0]['name'] === 'Mug'
        && $request['delete'] === [12]);
});

it('normalises trailing slashes in the store url and namespace', function () {
    config()->set('woocommerce.base_url', 'https://shop.test/');
    config()->set('woocommerce.namespace', '/wp-json/wc/v3/');
    app()->forgetInstance(Jeffersongoncalves\Woocommerce\Woocommerce::class);
    Woocommerce::clearResolvedInstances();

    Http::fake(['shop.test/wp-json/wc/v3/products*' => Http::response([])]);

    Woocommerce::listProducts();

    Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://shop.test/wp-json/wc/v3/products'));
});
