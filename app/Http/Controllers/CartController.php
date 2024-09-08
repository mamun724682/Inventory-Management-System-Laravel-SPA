<?php

namespace App\Http\Controllers;

use App\Enums\Cart\CartFiltersEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Enums\Product\ProductStatusEnum;
use App\Exceptions\CartException;
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

        // Get cart items
        $cartParams = [
            CartFiltersEnum::USER_ID->value => auth()->id(),
        ];

        return Inertia::render(
            component: 'Order/Pos',
            props: [
                'products' => $this->productService->getAll($productParams),
                'carts'    => $this->cartService->getAll($cartParams),
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

            Log::error("Something went wrong", [
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

            Log::error("Something went wrong", [
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
}
