<?php

namespace App\Http\Controllers;

use App\Enums\Cart\CartExpandEnum;
use App\Enums\Cart\CartFiltersEnum;
use App\Enums\Product\ProductExpandEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Enums\Product\ProductStatusEnum;
use App\Enums\Transaction\TransactionPaidThroughEnum;
use App\Exceptions\CartException;
use App\Exceptions\CartNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\Cart\CartQuantityUpdateRequest;
use App\Http\Requests\Product\ProductIndexRequest;
use App\Services\CartService;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CartService    $cartService,
    )
    {
    }

    public function index(ProductIndexRequest $request): Response
    {
        // Get products
        $productParams = $request->validated();
        $productParams[ProductFiltersEnum::STATUS->value] = ProductStatusEnum::ACTIVE;
        $productParams['expand'] = array_unique(array_merge($params['expand'] ?? [], [
            ProductExpandEnum::UNIT_TYPE->value,
        ]));

        // Get cart items
        $carts = $this->cartService->getAll([
            CartFiltersEnum::USER_ID->value => auth()->id(),
            "expand"                        => [
                CartExpandEnum::PRODUCT_UNIT_TYPE->value
            ],
            "per_page"                      => 500
        ]);

        // Calculate cart subtotal
        $cartSubtotal = 0;
        foreach ($carts as $cart) {
            $cartSubtotal += $cart->product->selling_price * $cart->quantity;
        }

        // Calculate total discount
        $discountData = BaseHelper::calculateDefaultDiscount(amount: $cartSubtotal);

        // Calculate total tax
        $taxData = BaseHelper::calculateTax(amount: $cartSubtotal);

        // Calculate total
        $total = BaseHelper::numberFormat(
            number: $cartSubtotal - $discountData["totalDiscount"] + $taxData["totalTax"]
        );

        return Inertia::render(
            component: 'Cart/Pos',
            props: [
                'products'         => $this->productService->getAll($productParams),
                'carts'            => $carts,
                'cartSubtotal'     => $cartSubtotal,
                'discountType'     => $discountData["discountType"],
                'discount'         => $discountData["discount"],
                'totalDiscount'    => $discountData["totalDiscount"],
                'tax'              => $taxData["tax"],
                'totalTax'         => $taxData["totalTax"],
                'total'            => $total,
                'orderPaidByTypes' => BaseHelper::convertKeyValueToLabelValueArray(TransactionPaidThroughEnum::choices()),
            ]
        );
    }

    /**
     * @param int $productId
     * @return RedirectResponse
     */
    public function addToCart(int $productId): RedirectResponse
    {
        try {
            $product = $this->productService->findByIdOrFail(id: $productId);

            $this->cartService->createOrUpdateForUser(
                product: $product,
                userId: auth()->id(),
            );

            $flash = [
                "message" => 'Product added to cart.'
            ];
        } catch (CartException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to add product to cart!",
            ];

            Log::error("Failed to add product to cart!", [
                "product_id" => $productId,
                "message"    => $e->getMessage(),
                "traces"     => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }

    /**
     * @param CartQuantityUpdateRequest $request
     * @param int $cartId
     * @return RedirectResponse
     */
    public function updateQuantity(CartQuantityUpdateRequest $request, int $cartId): RedirectResponse
    {
        try {
            $this->cartService->updateQuantity(
                id: $cartId,
                payload: $request->validated(),
            );

            $flash = [
                "message" => 'Product quantity updated.'
            ];
        } catch (CartException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to update quantity!",
            ];

            Log::error("Failed to update quantity!", [
                "cart_id" => $cartId,
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }

    /**
     * @param int $cartId
     * @return RedirectResponse
     */
    public function incrementQuantity(int $cartId): RedirectResponse
    {
        try {
            $this->cartService->incrementQuantity(id: $cartId);

            $flash = [
                "message" => 'Product quantity incremented.'
            ];
        } catch (CartException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to increment quantity!",
            ];

            Log::error("Failed to increment quantity!", [
                "cart_id" => $cartId,
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }

    /**
     * @param int $cartId
     * @return RedirectResponse
     */
    public function decrementQuantity(int $cartId): RedirectResponse
    {
        try {
            $this->cartService->decrementQuantity(id: $cartId);

            $flash = [
                "message" => 'Product quantity decremented.'
            ];
        } catch (CartException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to decrement quantity!",
            ];

            Log::error("Failed to decrement quantity!", [
                "cart_id" => $cartId,
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }

    /**
     * @param int $cartId
     * @return RedirectResponse
     */
    public function delete(int $cartId): RedirectResponse
    {
        try {
            $cart = $this->cartService->findByIdForUserOrFail(
                id: $cartId,
                userId: auth()->id()
            );
            $this->cartService->delete(cart: $cart);

            $flash = [
                "message" => 'Item deleted from cart.'
            ];
        } catch (CartNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to delete cart item!",
            ];

            Log::error("Failed to delete cart item!", [
                "cart_id" => $cartId,
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }

    /**
     * @return RedirectResponse
     */
    public function deleteForUser(): RedirectResponse
    {
        try {
            $this->cartService->deleteForUser(auth()->id());

            $flash = [
                "message" => 'All items are deleted from cart.'
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to delete cart all items!",
            ];

            Log::error("Failed to delete cart all items!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }
}
