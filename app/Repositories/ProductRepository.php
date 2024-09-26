<?php

namespace App\Repositories;

use App\Enums\Product\ProductFieldsEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class ProductRepository
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
     * @return Product|null
     */
    public function find(array $filters = [], array $expand = []): ?Product
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
            $product = Product::create($payload);
            DB::commit();
            return $product;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Product $product
     * @param array $changes
     * @return Product
     * @throws Exception
     */
    public function update(Product $product, array $changes): Product
    {
        $attempt = 1;
        do {
            $updated = $product->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during product update");
        }

        return $product->refresh();
    }

    /**
     * @param Product $product
     * @return bool|null
     */
    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return Product::query()
            ->when(isset($filters[ProductFiltersEnum::KEYWORD->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::ID->value, $filters[ProductFiltersEnum::KEYWORD->value])
                ->orWhere(ProductFieldsEnum::NAME->value, "like", "%" . $filters[ProductFiltersEnum::KEYWORD->value] . "%")
                ->orWhere(ProductFieldsEnum::PRODUCT_NUMBER->value, $filters[ProductFiltersEnum::KEYWORD->value])
                ->orWhere(ProductFieldsEnum::PRODUCT_CODE->value, $filters[ProductFiltersEnum::KEYWORD->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::ID->value, $filters[ProductFiltersEnum::ID->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::CATEGORY_ID->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::CATEGORY_ID->value, $filters[ProductFiltersEnum::CATEGORY_ID->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::SUPPLIER_ID->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::SUPPLIER_ID->value, $filters[ProductFiltersEnum::SUPPLIER_ID->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::NAME->value, "like", "%" . $filters[ProductFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[ProductFiltersEnum::PRODUCT_NUMBER->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::PRODUCT_NUMBER->value, $filters[ProductFiltersEnum::PRODUCT_NUMBER->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::PRODUCT_CODE->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::PRODUCT_CODE->value, $filters[ProductFiltersEnum::PRODUCT_CODE->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::UNIT_TYPE_ID->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::UNIT_TYPE_ID->value, $filters[ProductFiltersEnum::UNIT_TYPE_ID->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::QUANTITY->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::QUANTITY->value, $filters[ProductFiltersEnum::QUANTITY->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::QUANTITIES->value]), function ($query) use ($filters) {
                $query->whereBetween(ProductFieldsEnum::QUANTITY->value, $filters[ProductFiltersEnum::QUANTITIES->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::BUYING_PRICE->value]), function ($query) use ($filters) {
                $query->whereBetween(ProductFieldsEnum::BUYING_PRICE->value, $filters[ProductFiltersEnum::BUYING_PRICE->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::SELLING_PRICE->value]), function ($query) use ($filters) {
                $query->whereBetween(ProductFieldsEnum::SELLING_PRICE->value, $filters[ProductFiltersEnum::SELLING_PRICE->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::STATUS->value]), function ($query) use ($filters) {
                $query->where(ProductFieldsEnum::STATUS->value, $filters[ProductFiltersEnum::STATUS->value]);
            })
            ->when(isset($filters[ProductFiltersEnum::BUYING_DATE->value]), function ($query) use ($filters) {
                $query->whereBetween(ProductFieldsEnum::BUYING_DATE->value, [
                    $filters[ProductFiltersEnum::BUYING_DATE->value][0],
                    $filters[ProductFiltersEnum::BUYING_DATE->value][1] ?? Carbon::parse($filters[ProductFiltersEnum::BUYING_DATE->value][0])->endOfDay()->format("Y-m-d H:i:s")
                ]);
            })
            ->when(isset($filters[ProductFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(ProductFieldsEnum::CREATED_AT->value, [
                    $filters[ProductFiltersEnum::CREATED_AT->value][0],
                    $filters[ProductFiltersEnum::CREATED_AT->value][1] ?? Carbon::parse($filters[ProductFiltersEnum::CREATED_AT->value][0])->endOfDay()->format("Y-m-d H:i:s")
                ]);
            });
    }
}
