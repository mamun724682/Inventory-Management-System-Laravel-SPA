<?php

namespace App\Services;

use App\Enums\Cart\CartExpandEnum;
use App\Enums\Cart\CartFieldsEnum;
use App\Enums\Cart\CartFiltersEnum;
use App\Enums\Core\SortOrderEnum;
use App\Exceptions\CartException;
use App\Exceptions\CartNotFoundException;
use App\Exceptions\DBCommitException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CartService
{
    public function __construct(private readonly CartRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, CartFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? CartFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Cart|null
     * @throws CartNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Cart
    {
        $cart = $this->repository->find([
            CartFiltersEnum::ID->value => $id
        ], $expands);

        if (!$cart) {
            throw new CartNotFoundException('Cart not found by the given id.');
        }

        return $cart;
    }

    /**
     * @param int $id
     * @param int $userId
     * @param array $expands
     * @return Cart|null
     * @throws CartNotFoundException
     */
    public function findByIdForUserOrFail(int $id, int $userId, array $expands = []): ?Cart
    {
        $cart = $this->repository->find([
            CartFiltersEnum::ID->value      => $id,
            CartFiltersEnum::USER_ID->value => $userId
        ], $expands);

        if (!$cart) {
            throw new CartNotFoundException('Cart not found by the given id.');
        }

        return $cart;
    }

    /**
     * @param int $productId
     * @param int $userId
     * @param array $expands
     * @return Cart|null
     */
    public function findByProductAndUser(int $productId, int $userId, array $expands = []): ?Cart
    {
        return $this->repository->find([
            CartFiltersEnum::PRODUCT_ID->value => $productId,
            CartFiltersEnum::USER_ID->value    => $userId
        ], $expands);
    }

    /**
     * @param Product $product
     * @param int $userId
     * @return Cart
     * @throws CartException
     * @throws DBCommitException
     */
    public function createOrUpdateForUser(Product $product, int $userId): Cart
    {
        $cart = $this->findByProductAndUser(
            productId: $product->id,
            userId: $userId
        );

        if ($product->quantity < 1) {
            throw new CartException("Product quantity not available.");
        }

        if ($cart) {
            if ($product->quantity < ($cart->quantity + 1)) {
                throw new CartException("Only {$product->quantity} products are available.");
            }

            $cart->increment(column: CartFieldsEnum::QUANTITY->value);
            return $cart->refresh();
        }

        $processPayload = [
            CartFieldsEnum::USER_ID->value    => $userId,
            CartFieldsEnum::PRODUCT_ID->value => $product->id,
            CartFieldsEnum::QUANTITY->value   => 1,
        ];

        return $this->repository->create(payload: $processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return Cart
     * @throws CartException
     * @throws CartNotFoundException
     */
    public function updateQuantity(int $id, array $payload): Cart
    {
        $cart = $this->findByIdOrFail(
            id: $id,
            expands: [CartExpandEnum::PRODUCT->value]
        );

        if ($cart->product->quantity < $payload[CartFieldsEnum::QUANTITY->value]) {
            throw new CartException("Only {$cart->product->quantity} products are available.");
        }

        $processPayload = [
            CartFieldsEnum::QUANTITY->value => $payload[CartFieldsEnum::QUANTITY->value],
        ];

        return $this->repository->update(
            cart: $cart,
            changes: $processPayload
        );
    }

    public function incrementQuantity(int $id): Cart
    {
        $cart = $this->findByIdOrFail(
            id: $id,
            expands: [CartExpandEnum::PRODUCT->value]
        );

        if ($cart->product->quantity < ($cart->quantity + 1)) {
            throw new CartException("Only {$cart->product->quantity} products are available.");
        }

        $processPayload = [
            CartFieldsEnum::QUANTITY->value => $cart->quantity + 1,
        ];

        return $this->repository->update(
            cart: $cart,
            changes: $processPayload
        );
    }

    /**
     * @param int $id
     * @return Cart
     * @throws CartException
     * @throws CartNotFoundException
     * @throws Exception
     */
    public function decrementQuantity(int $id): Cart
    {
        $cart = $this->findByIdOrFail(
            id: $id,
            expands: [CartExpandEnum::PRODUCT->value]
        );

        if ($cart->quantity == 1) {
            throw new CartException("Quantity cannot be zero.");
        }

        if (!$cart->product->quantity) {
            throw new CartException("Product quantity not available.");
        }

        if ($cart->product->quantity < ($cart->quantity - 1)) {
            throw new CartException("Only {$cart->product->quantity} products are available.");
        }

        $processPayload = [
            CartFieldsEnum::QUANTITY->value => $cart->quantity - 1,
        ];

        return $this->repository->update(
            cart: $cart,
            changes: $processPayload
        );
    }

    /**
     * @param Cart $cart
     * @return bool|null
     */
    public function delete(Cart $cart): ?bool
    {
        return $this->repository->delete(cart: $cart);
    }

    /**
     * @param int $userId
     * @return void
     */
    public function deleteForUser(int $userId): void
    {
        $carts = $this->getAll([
            CartFiltersEnum::USER_ID->value => $userId,
            "per_page" => 500
        ]);

        Cart::destroy($carts->pluck('id')->toArray());
    }
}
