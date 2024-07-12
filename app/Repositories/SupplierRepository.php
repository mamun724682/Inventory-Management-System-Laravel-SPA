<?php

namespace App\Repositories;

use App\Enums\Supplier\SupplierFieldsEnum;
use App\Enums\Supplier\SupplierFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Supplier;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class SupplierRepository
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
     * @return Supplier|null
     */
    public function find(array $filters = [], array $expand = []): ?Supplier
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
            $supplier = Supplier::create($payload);
            DB::commit();
            return $supplier;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Supplier $supplier
     * @param array $changes
     * @return Supplier
     * @throws Exception
     */
    public function update(Supplier $supplier, array $changes): Supplier
    {
        $attempt = 1;
        do {
            $updated = $supplier->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during supplier update");
        }

        return $supplier->refresh();
    }

    /**
     * @param Supplier $supplier
     * @return bool|null
     */
    public function delete(Supplier $supplier): ?bool
    {
        return $supplier->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return Supplier::query()
            ->when(isset($filters[SupplierFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(SupplierFieldsEnum::ID->value, $filters[SupplierFiltersEnum::ID->value]);
            })
            ->when(isset($filters[SupplierFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(SupplierFieldsEnum::NAME->value, "like", "%" . $filters[SupplierFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[SupplierFiltersEnum::EMAIL->value]), function ($query) use ($filters) {
                $query->where(SupplierFieldsEnum::EMAIL->value, $filters[SupplierFiltersEnum::EMAIL->value]);
            })
            ->when(isset($filters[SupplierFiltersEnum::PHONE->value]), function ($query) use ($filters) {
                $query->where(SupplierFieldsEnum::PHONE->value, "like", "%" . $filters[SupplierFiltersEnum::PHONE->value] . "%");
            })
            ->when(isset($filters[SupplierFiltersEnum::SHOP_NAME->value]), function ($query) use ($filters) {
                $query->where(SupplierFieldsEnum::SHOP_NAME->value, "like", "%" . $filters[SupplierFiltersEnum::SHOP_NAME->value] . "%");
            })
            ->when(isset($filters[SupplierFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(SupplierFieldsEnum::CREATED_AT->value, $filters[SupplierFiltersEnum::CREATED_AT->value]);
            });
    }
}
