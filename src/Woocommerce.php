<?php

namespace Jeffersongoncalves\Woocommerce;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Woocommerce
{
    public function __construct(
        protected string $baseUrl,
        protected string $consumerKey,
        protected string $consumerSecret,
        protected string $namespace = 'wp-json/wc/v3',
    ) {}

    protected function client(): PendingRequest
    {
        return Http::baseUrl(rtrim($this->baseUrl, '/').'/'.trim($this->namespace, '/'))
            ->withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->acceptJson();
    }

    // --- Products ---

    public function listProducts(array $filters = []): Response
    {
        return $this->client()->get('/products', $filters);
    }

    public function getProduct(int $id): Response
    {
        return $this->client()->get("/products/{$id}");
    }

    public function createProduct(array $attributes): Response
    {
        return $this->client()->post('/products', $attributes);
    }

    public function updateProduct(int $id, array $attributes): Response
    {
        return $this->client()->put("/products/{$id}", $attributes);
    }

    public function deleteProduct(int $id, bool $force = false): Response
    {
        return $this->client()->delete("/products/{$id}", ['force' => $force]);
    }

    // --- Product variations ---

    public function listVariations(int $productId, array $filters = []): Response
    {
        return $this->client()->get("/products/{$productId}/variations", $filters);
    }

    public function getVariation(int $productId, int $id): Response
    {
        return $this->client()->get("/products/{$productId}/variations/{$id}");
    }

    public function createVariation(int $productId, array $attributes): Response
    {
        return $this->client()->post("/products/{$productId}/variations", $attributes);
    }

    public function updateVariation(int $productId, int $id, array $attributes): Response
    {
        return $this->client()->put("/products/{$productId}/variations/{$id}", $attributes);
    }

    public function deleteVariation(int $productId, int $id, bool $force = true): Response
    {
        return $this->client()->delete("/products/{$productId}/variations/{$id}", ['force' => $force]);
    }

    // --- Product categories ---

    public function listProductCategories(array $filters = []): Response
    {
        return $this->client()->get('/products/categories', $filters);
    }

    public function getProductCategory(int $id): Response
    {
        return $this->client()->get("/products/categories/{$id}");
    }

    public function createProductCategory(array $attributes): Response
    {
        return $this->client()->post('/products/categories', $attributes);
    }

    public function updateProductCategory(int $id, array $attributes): Response
    {
        return $this->client()->put("/products/categories/{$id}", $attributes);
    }

    public function deleteProductCategory(int $id): Response
    {
        return $this->client()->delete("/products/categories/{$id}", ['force' => true]);
    }

    // --- Product tags ---

    public function listProductTags(array $filters = []): Response
    {
        return $this->client()->get('/products/tags', $filters);
    }

    public function getProductTag(int $id): Response
    {
        return $this->client()->get("/products/tags/{$id}");
    }

    public function createProductTag(array $attributes): Response
    {
        return $this->client()->post('/products/tags', $attributes);
    }

    public function updateProductTag(int $id, array $attributes): Response
    {
        return $this->client()->put("/products/tags/{$id}", $attributes);
    }

    public function deleteProductTag(int $id): Response
    {
        return $this->client()->delete("/products/tags/{$id}", ['force' => true]);
    }

    // --- Orders ---

    public function listOrders(array $filters = []): Response
    {
        return $this->client()->get('/orders', $filters);
    }

    public function getOrder(int $id): Response
    {
        return $this->client()->get("/orders/{$id}");
    }

    public function createOrder(array $attributes): Response
    {
        return $this->client()->post('/orders', $attributes);
    }

    public function updateOrder(int $id, array $attributes): Response
    {
        return $this->client()->put("/orders/{$id}", $attributes);
    }

    public function deleteOrder(int $id, bool $force = false): Response
    {
        return $this->client()->delete("/orders/{$id}", ['force' => $force]);
    }

    // --- Order notes ---

    public function listOrderNotes(int $orderId, array $filters = []): Response
    {
        return $this->client()->get("/orders/{$orderId}/notes", $filters);
    }

    public function getOrderNote(int $orderId, int $id): Response
    {
        return $this->client()->get("/orders/{$orderId}/notes/{$id}");
    }

    public function createOrderNote(int $orderId, string $note, bool $customerNote = false): Response
    {
        return $this->client()->post("/orders/{$orderId}/notes", [
            'note' => $note,
            'customer_note' => $customerNote,
        ]);
    }

    public function deleteOrderNote(int $orderId, int $id): Response
    {
        return $this->client()->delete("/orders/{$orderId}/notes/{$id}", ['force' => true]);
    }

    // --- Refunds ---

    public function listRefunds(int $orderId, array $filters = []): Response
    {
        return $this->client()->get("/orders/{$orderId}/refunds", $filters);
    }

    public function getRefund(int $orderId, int $id): Response
    {
        return $this->client()->get("/orders/{$orderId}/refunds/{$id}");
    }

    public function createRefund(int $orderId, array $attributes): Response
    {
        return $this->client()->post("/orders/{$orderId}/refunds", $attributes);
    }

    public function deleteRefund(int $orderId, int $id): Response
    {
        return $this->client()->delete("/orders/{$orderId}/refunds/{$id}", ['force' => true]);
    }

    // --- Customers ---

    public function listCustomers(array $filters = []): Response
    {
        return $this->client()->get('/customers', $filters);
    }

    public function getCustomer(int $id): Response
    {
        return $this->client()->get("/customers/{$id}");
    }

    public function createCustomer(array $attributes): Response
    {
        return $this->client()->post('/customers', $attributes);
    }

    public function updateCustomer(int $id, array $attributes): Response
    {
        return $this->client()->put("/customers/{$id}", $attributes);
    }

    public function deleteCustomer(int $id, ?int $reassign = null): Response
    {
        return $this->client()->delete("/customers/{$id}", array_filter([
            'force' => true,
            'reassign' => $reassign,
        ], fn ($value) => $value !== null));
    }

    // --- Coupons ---

    public function listCoupons(array $filters = []): Response
    {
        return $this->client()->get('/coupons', $filters);
    }

    public function getCoupon(int $id): Response
    {
        return $this->client()->get("/coupons/{$id}");
    }

    public function createCoupon(array $attributes): Response
    {
        return $this->client()->post('/coupons', $attributes);
    }

    public function updateCoupon(int $id, array $attributes): Response
    {
        return $this->client()->put("/coupons/{$id}", $attributes);
    }

    public function deleteCoupon(int $id, bool $force = false): Response
    {
        return $this->client()->delete("/coupons/{$id}", ['force' => $force]);
    }

    // --- Webhooks ---

    public function listWebhooks(array $filters = []): Response
    {
        return $this->client()->get('/webhooks', $filters);
    }

    public function getWebhook(int $id): Response
    {
        return $this->client()->get("/webhooks/{$id}");
    }

    public function createWebhook(string $topic, string $deliveryUrl, array $attributes = []): Response
    {
        return $this->client()->post('/webhooks', [
            'topic' => $topic,
            'delivery_url' => $deliveryUrl,
            ...$attributes,
        ]);
    }

    public function updateWebhook(int $id, array $attributes): Response
    {
        return $this->client()->put("/webhooks/{$id}", $attributes);
    }

    public function deleteWebhook(int $id): Response
    {
        return $this->client()->delete("/webhooks/{$id}", ['force' => true]);
    }

    // --- Tax rates ---

    public function listTaxRates(array $filters = []): Response
    {
        return $this->client()->get('/taxes', $filters);
    }

    public function getTaxRate(int $id): Response
    {
        return $this->client()->get("/taxes/{$id}");
    }

    public function createTaxRate(array $attributes): Response
    {
        return $this->client()->post('/taxes', $attributes);
    }

    public function updateTaxRate(int $id, array $attributes): Response
    {
        return $this->client()->put("/taxes/{$id}", $attributes);
    }

    public function deleteTaxRate(int $id): Response
    {
        return $this->client()->delete("/taxes/{$id}", ['force' => true]);
    }

    // --- Payment gateways and shipping ---

    public function listPaymentGateways(): Response
    {
        return $this->client()->get('/payment_gateways');
    }

    public function getPaymentGateway(string $id): Response
    {
        return $this->client()->get("/payment_gateways/{$id}");
    }

    public function updatePaymentGateway(string $id, array $attributes): Response
    {
        return $this->client()->put("/payment_gateways/{$id}", $attributes);
    }

    public function listShippingZones(): Response
    {
        return $this->client()->get('/shipping/zones');
    }

    public function getShippingZone(int $id): Response
    {
        return $this->client()->get("/shipping/zones/{$id}");
    }

    public function listShippingZoneMethods(int $zoneId): Response
    {
        return $this->client()->get("/shipping/zones/{$zoneId}/methods");
    }

    // --- Reports and status ---

    public function salesReport(array $filters = []): Response
    {
        return $this->client()->get('/reports/sales', $filters);
    }

    public function topSellersReport(array $filters = []): Response
    {
        return $this->client()->get('/reports/top_sellers', $filters);
    }

    public function systemStatus(): Response
    {
        return $this->client()->get('/system_status');
    }

    /**
     * Batch create/update/delete on any collection that supports it, e.g.
     * batch('products', ['create' => [...], 'update' => [...], 'delete' => [12]]).
     */
    public function batch(string $resource, array $payload): Response
    {
        return $this->client()->post(trim($resource, '/').'/batch', $payload);
    }
}
