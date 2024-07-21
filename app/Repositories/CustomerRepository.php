<?php

namespace App\Repositories;

use App\Enums\Customer\CustomerFieldsEnum;
use App\Enums\Customer\CustomerFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class CustomerRepository
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
     * @return Customer|null
     */
    public function find(array $filters = [], array $expand = []): ?Customer
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
            $customer = Customer::create($payload);
            DB::commit();
            return $customer;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Customer $customer
     * @param array $changes
     * @return Customer
     * @throws Exception
     */
    public function update(Customer $customer, array $changes): Customer
    {
        $attempt = 1;
        do {
            $updated = $customer->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during customer update");
        }

        return $customer->refresh();
    }

    /**
     * @param Customer $customer
     * @return bool|null
     */
    public function delete(Customer $customer): ?bool
    {
        return $customer->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return Customer::query()
            ->when(isset($filters[CustomerFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(CustomerFieldsEnum::ID->value, $filters[CustomerFiltersEnum::ID->value]);
            })
            ->when(isset($filters[CustomerFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(CustomerFieldsEnum::NAME->value, "like", "%" . $filters[CustomerFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[CustomerFiltersEnum::EMAIL->value]), function ($query) use ($filters) {
                $query->where(CustomerFieldsEnum::EMAIL->value, $filters[CustomerFiltersEnum::EMAIL->value]);
            })
            ->when(isset($filters[CustomerFiltersEnum::PHONE->value]), function ($query) use ($filters) {
                $query->where(CustomerFieldsEnum::PHONE->value, "like", "%" . $filters[CustomerFiltersEnum::PHONE->value] . "%");
            })
            ->when(isset($filters[CustomerFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(CustomerFieldsEnum::CREATED_AT->value, $filters[CustomerFiltersEnum::CREATED_AT->value]);
            });
    }
}
