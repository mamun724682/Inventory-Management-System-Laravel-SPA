<?php

namespace App\Repositories;

use App\Enums\Cart\CartFieldsEnum;
use App\Enums\Cart\CartFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Cart;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class CartRepository
{
    const MAX_RETRY = 5;

    /**
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @param array $fields
     * @param array $expand
     * @param string $sortBy
     * @param string $sortOrder
     * @return LengthAwarePaginator
     */
    public function getAll(
        int    $page,
        int    $perPage,
        array  $filters = [],
        array  $fields = [],
        array  $expand = [],
        string $sortBy = "id",
        string $sortOrder = "ASC"
    ): LengthAwarePaginator
    {
        $query = $this->getQuery($filters)
            ->orderBy($sortBy, $sortOrder)
            ->with($expand);

        if (count($fields) > 0) {
            $query = $query->select($fields);
        }

        return $query->paginate(
            perPage: $perPage,
            page: $page
        )->withQueryString();
    }

    /**
     * @param array $filters
     * @return bool
     */
    public function exists(array $filters = []): bool
    {
        return $this->getQuery($filters)->exists();
    }

    /**
     * @param array $filters
     * @param array $expand
     * @return Cart|null
     */
    public function find(array $filters = [], array $expand = []): ?Cart
    {
        return $this->getQuery($filters)
            ->with($expand)
            ->first();
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     */
    public function create(array $payload): mixed
    {
        try {
            DB::beginTransaction();
            $cart = Cart::create($payload);
            DB::commit();
            return $cart;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Cart $cart
     * @param array $changes
     * @return Cart
     * @throws Exception
     */
    public function update(Cart $cart, array $changes): Cart
    {
        $attempt = 1;
        do {
            $updated = $cart->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during cart update");
        }

        return $cart->refresh();
    }

    /**
     * @param Cart $cart
     * @return bool|null
     */
    public function delete(Cart $cart): ?bool
    {
        return $cart->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return Cart::query()
            ->when(isset($filters[CartFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(CartFieldsEnum::ID->value, $filters[CartFiltersEnum::ID->value]);
            })
            ->when(isset($filters[CartFiltersEnum::USER_ID->value]), function ($query) use ($filters) {
                $query->where(CartFieldsEnum::USER_ID->value, $filters[CartFiltersEnum::USER_ID->value]);
            })
            ->when(isset($filters[CartFiltersEnum::PRODUCT_ID->value]), function ($query) use ($filters) {
                $query->where(CartFieldsEnum::PRODUCT_ID->value, $filters[CartFiltersEnum::PRODUCT_ID->value]);
            })
            ->when(isset($filters[CartFiltersEnum::QUANTITY->value]), function ($query) use ($filters) {
                $query->where(CartFieldsEnum::QUANTITY->value, $filters[CartFiltersEnum::QUANTITY->value]);
            })
            ->when(isset($filters[CartFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(CartFieldsEnum::CREATED_AT->value, [
                    $filters[CartFiltersEnum::CREATED_AT->value][0],
                    $filters[CartFiltersEnum::CREATED_AT->value][1] ?? Carbon::parse($filters[CartFiltersEnum::CREATED_AT->value][0])->endOfDay()->format("Y-m-d H:i:s")
                ]);
            });
    }
}
