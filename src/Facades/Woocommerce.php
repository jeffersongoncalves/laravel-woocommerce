<?php

namespace Jeffersongoncalves\Woocommerce\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response listProducts(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getProduct(int $id)
 * @method static \Illuminate\Http\Client\Response createProduct(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateProduct(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteProduct(int $id, bool $force = false)
 * @method static \Illuminate\Http\Client\Response listVariations(int $productId, array $filters = [])
 * @method static \Illuminate\Http\Client\Response getVariation(int $productId, int $id)
 * @method static \Illuminate\Http\Client\Response createVariation(int $productId, array $attributes)
 * @method static \Illuminate\Http\Client\Response updateVariation(int $productId, int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteVariation(int $productId, int $id, bool $force = true)
 * @method static \Illuminate\Http\Client\Response listProductCategories(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getProductCategory(int $id)
 * @method static \Illuminate\Http\Client\Response createProductCategory(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateProductCategory(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteProductCategory(int $id)
 * @method static \Illuminate\Http\Client\Response listProductTags(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getProductTag(int $id)
 * @method static \Illuminate\Http\Client\Response createProductTag(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateProductTag(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteProductTag(int $id)
 * @method static \Illuminate\Http\Client\Response listOrders(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getOrder(int $id)
 * @method static \Illuminate\Http\Client\Response createOrder(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateOrder(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteOrder(int $id, bool $force = false)
 * @method static \Illuminate\Http\Client\Response listOrderNotes(int $orderId, array $filters = [])
 * @method static \Illuminate\Http\Client\Response getOrderNote(int $orderId, int $id)
 * @method static \Illuminate\Http\Client\Response createOrderNote(int $orderId, string $note, bool $customerNote = false)
 * @method static \Illuminate\Http\Client\Response deleteOrderNote(int $orderId, int $id)
 * @method static \Illuminate\Http\Client\Response listRefunds(int $orderId, array $filters = [])
 * @method static \Illuminate\Http\Client\Response getRefund(int $orderId, int $id)
 * @method static \Illuminate\Http\Client\Response createRefund(int $orderId, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteRefund(int $orderId, int $id)
 * @method static \Illuminate\Http\Client\Response listCustomers(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getCustomer(int $id)
 * @method static \Illuminate\Http\Client\Response createCustomer(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateCustomer(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteCustomer(int $id, ?int $reassign = null)
 * @method static \Illuminate\Http\Client\Response listCoupons(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getCoupon(int $id)
 * @method static \Illuminate\Http\Client\Response createCoupon(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateCoupon(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteCoupon(int $id, bool $force = false)
 * @method static \Illuminate\Http\Client\Response listWebhooks(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getWebhook(int $id)
 * @method static \Illuminate\Http\Client\Response createWebhook(string $topic, string $deliveryUrl, array $attributes = [])
 * @method static \Illuminate\Http\Client\Response updateWebhook(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteWebhook(int $id)
 * @method static \Illuminate\Http\Client\Response listTaxRates(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getTaxRate(int $id)
 * @method static \Illuminate\Http\Client\Response createTaxRate(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateTaxRate(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteTaxRate(int $id)
 * @method static \Illuminate\Http\Client\Response listPaymentGateways()
 * @method static \Illuminate\Http\Client\Response getPaymentGateway(string $id)
 * @method static \Illuminate\Http\Client\Response updatePaymentGateway(string $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response listShippingZones()
 * @method static \Illuminate\Http\Client\Response getShippingZone(int $id)
 * @method static \Illuminate\Http\Client\Response listShippingZoneMethods(int $zoneId)
 * @method static \Illuminate\Http\Client\Response salesReport(array $filters = [])
 * @method static \Illuminate\Http\Client\Response topSellersReport(array $filters = [])
 * @method static \Illuminate\Http\Client\Response systemStatus()
 * @method static \Illuminate\Http\Client\Response batch(string $resource, array $payload)
 *
 * @see \Jeffersongoncalves\Woocommerce\Woocommerce
 */
class Woocommerce extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jeffersongoncalves\Woocommerce\Woocommerce::class;
    }
}
