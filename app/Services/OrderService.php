<?php

namespace App\Services;

use App\Enums\Cart\CartExpandEnum;
use App\Enums\Cart\CartFiltersEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Order\OrderFieldsEnum;
use App\Enums\Order\OrderFiltersEnum;
use App\Enums\Order\OrderStatusEnum;
use App\Enums\OrderItem\OrderItemFieldsEnum;
use App\Enums\Product\ProductStatusEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\OrderCreateException;
use App\Exceptions\OrderNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly OrderRepository  $repository,
        private readonly OrderItemService $orderItemService,
        private readonly CartService      $cartService,
    )
    {
    }

    /**
     * @param array $queryParameters
     * @return LengthAwarePaginator
     */
    public function getAll(array $queryParameters): LengthAwarePaginator
    {
        $page = $queryParameters["page"] ?? 1;
        $perPage = BaseHelper::perPage($queryParameters["per_page"] ?? null);

        return $this->repository->getAll(
            page: $page,
            perPage: $perPage,
            filters: ArrayHelper::getFiltersValues($queryParameters, OrderFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? OrderFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Order|null
     * @throws OrderNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Order
    {
        $order = $this->repository->find([
            OrderFiltersEnum::ID->value => $id
        ], $expands);

        if (!$order) {
            throw new OrderNotFoundException('Order not found by the given id.');
        }

        return $order;
    }

    /**
     * @param array $payload
     * @param int $userId
     * @return Order
     * @throws DBCommitException
     * @throws OrderCreateException
     */
    public function createForUser(array $payload, int $userId): Order
    {
        try {
            DB::beginTransaction();
            // Get carts for user
            $carts = $this->cartService->getAll([
                CartFiltersEnum::USER_ID->value => $userId,
                "expand"                        => [CartExpandEnum::PRODUCT->value],
                "per_page"                      => 500,
            ]);

            // Calculate sub_total
            $cartSubtotal = 0;
            $productBuyingSubtotal = 0;
            $processOrderItemPayloads = [];
            foreach ($carts as $cart) {
                // Check cart product is active
                if ($cart->product->status == ProductStatusEnum::INACTIVE->value) {
                    throw new OrderCreateException("Product id {$cart->product_id} is not active now.");
                }

                // Check product quantity is available
                if ($cart->quantity > $cart->product->quantity) {
                    throw new OrderCreateException("Product quantity not available for id {$cart->product_id}.");
                }

                $cartSubtotal += $cart->product->selling_price * $cart->quantity;
                $productBuyingSubtotal += $cart->product->buying_price * $cart->quantity;

                $processOrderItemPayloads[] = [
                    OrderItemFieldsEnum::PRODUCT_ID->value   => $cart->product->id,
                    OrderItemFieldsEnum::PRODUCT_JSON->value => $cart->product->toArray(),
                    OrderItemFieldsEnum::QUANTITY->value     => $cart->quantity,
                ];

                // Delete cart
                $cart->delete();
            }

            // Calculate tax_total
            $taxData = BaseHelper::calculateTax(amount: $cartSubtotal);
            $taxTotal = $taxData["totalTax"];

            // Calculate discount_total with custom discount
            $discountDefaultData = BaseHelper::calculateDefaultDiscount(amount: $cartSubtotal);
            $customDiscount = $payload['custom_discount'] ?? [];
            $discountTotal = $discountDefaultData['totalDiscount'];
            if ($customDiscount){
                $customDiscountData = BaseHelper::calculateCustomDiscount(
                    amount: $cartSubtotal,
                    discount: $customDiscount['discount'],
                    discountType: $customDiscount['discount_type']
                );
                $discountTotal = $customDiscountData['totalDiscount'] + $discountTotal;
            }

            // Calculate total
            $total = $cartSubtotal - $discountTotal;
            $totalWithTax = $cartSubtotal - $discountTotal + $taxTotal;

            // Calculate due
            $paid = $payload[OrderFieldsEnum::PAID->value] ?? 0;
            $due = $total - $paid;
            $dueWithTax = $totalWithTax - $paid;

            // Calculate profit
            $profit = $total - $productBuyingSubtotal - $due;
            $profit = $profit < 0 ? 0 : $profit;

            // Calculate loss
            $loss = $profit < 0 ? abs($profit) : 0;

            // Decide status
            if ($paid == 0){
                $status = OrderStatusEnum::UNPAID->value;
            } elseif ($paid == $totalWithTax) {
                $status = OrderStatusEnum::PAID->value;
            } else {
                $status = OrderStatusEnum::PARTIAL_PAID->value;
            }

            $processPayload = [
                OrderFieldsEnum::CUSTOMER_ID->value    => $payload[OrderFieldsEnum::CUSTOMER_ID->value] ?? null,
                OrderFieldsEnum::ORDER_NUMBER->value   => 'O-' . Str::random(5),
                OrderFieldsEnum::SUB_TOTAL->value      => $cartSubtotal,
                OrderFieldsEnum::TAX_TOTAL->value      => $taxTotal,
                OrderFieldsEnum::DISCOUNT_TOTAL->value => $discountTotal,
                OrderFieldsEnum::TOTAL->value          => $totalWithTax,
                OrderFieldsEnum::PAID->value           => $paid,
                OrderFieldsEnum::DUE->value            => $dueWithTax,
                OrderFieldsEnum::PAID_BY->value        => $payload[OrderFieldsEnum::PAID_BY->value],
                OrderFieldsEnum::PROFIT->value         => $profit,
                OrderFieldsEnum::LOSS->value           => $loss,
                OrderFieldsEnum::STATUS->value         => $status,
            ];

            // Create order
            $order = $this->repository->create($processPayload);

            // Insert order items
            $this->orderItemService->insert(
                payloads: $processOrderItemPayloads,
                orderId: $order->id
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $order;
    }

    private function decideStatus()
    {

    }

    /**
     * @param int $id
     * @param array $payload
     * @return Order
     * @throws OrderNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): Order
    {
        $order = $this->findByIdOrFail($id);

        $processPayload = [
            OrderFieldsEnum::CATEGORY_ID->value   => $payload[OrderFieldsEnum::CATEGORY_ID->value] ?? $order->category_id,
            OrderFieldsEnum::SUPPLIER_ID->value   => $payload[OrderFieldsEnum::SUPPLIER_ID->value] ?? $order->supplier_id,
            OrderFieldsEnum::NAME->value          => $payload[OrderFieldsEnum::NAME->value] ?? $order->name,
            OrderFieldsEnum::DESCRIPTION->value   => $payload[OrderFieldsEnum::DESCRIPTION->value] ?? $order->description,
            OrderFieldsEnum::PRODUCT_CODE->value  => $payload[OrderFieldsEnum::PRODUCT_CODE->value] ?? $order->order_code,
            OrderFieldsEnum::ROOT->value          => $payload[OrderFieldsEnum::ROOT->value] ?? $order->root,
            OrderFieldsEnum::BUYING_PRICE->value  => $payload[OrderFieldsEnum::BUYING_PRICE->value] ?? $order->buying_price,
            OrderFieldsEnum::SELLING_PRICE->value => $payload[OrderFieldsEnum::SELLING_PRICE->value] ?? $order->selling_price,
            OrderFieldsEnum::BUYING_DATE->value   => $payload[OrderFieldsEnum::BUYING_DATE->value] ?? $order->buying_date,
            OrderFieldsEnum::QUANTITY->value      => $payload[OrderFieldsEnum::QUANTITY->value] ?? $order->quantity,
            OrderFieldsEnum::PHOTO->value         => $photo,
            OrderFieldsEnum::STATUS->value        => $payload[OrderFieldsEnum::STATUS->value] ?? $order->status,
        ];

        return $this->repository->update($order, $processPayload);
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws OrderNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $order = $this->findByIdOrFail($id);
        return $this->repository->delete($order);
    }
}
