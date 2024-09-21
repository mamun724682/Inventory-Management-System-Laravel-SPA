<?php

namespace App\Repositories;

use App\Enums\Order\OrderFieldsEnum;
use App\Enums\Order\OrderFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class OrderRepository
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
     * @param array $expand
     * @return Order|null
     */
    public function find(array $filters = [], array $expand = []): ?Order
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
            $order = Order::create($payload);
            DB::commit();
            return $order;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Order $order
     * @param array $changes
     * @return Order
     * @throws Exception
     */
    public function update(Order $order, array $changes): Order
    {
        $attempt = 1;
        do {
            $updated = $order->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during order update");
        }

        return $order->refresh();
    }

    /**
     * @param Order $order
     * @return bool|null
     */
    public function delete(Order $order): ?bool
    {
        return $order->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return Order::query()
            ->when(isset($filters[OrderFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(OrderFieldsEnum::ID->value, $filters[OrderFiltersEnum::ID->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::CUSTOMER_ID->value]), function ($query) use ($filters) {
                $query->where(OrderFieldsEnum::CUSTOMER_ID->value, $filters[OrderFiltersEnum::CUSTOMER_ID->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::ORDER_NUMBER->value]), function ($query) use ($filters) {
                $query->where(OrderFieldsEnum::ORDER_NUMBER->value, $filters[OrderFiltersEnum::ORDER_NUMBER->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::SUB_TOTAL->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderFieldsEnum::SUB_TOTAL->value, $filters[OrderFiltersEnum::SUB_TOTAL->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::TOTAL->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderFieldsEnum::TOTAL->value, $filters[OrderFiltersEnum::TOTAL->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::DUE->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderFieldsEnum::DUE->value, $filters[OrderFiltersEnum::DUE->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::PROFIT->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderFieldsEnum::PROFIT->value, $filters[OrderFiltersEnum::PROFIT->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::LOSS->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderFieldsEnum::LOSS->value, $filters[OrderFiltersEnum::LOSS->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::STATUS->value]), function ($query) use ($filters) {
                $query->where(OrderFieldsEnum::STATUS->value, $filters[OrderFiltersEnum::STATUS->value]);
            })
            ->when(isset($filters[OrderFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderFieldsEnum::CREATED_AT->value, [
                    $filters[OrderFiltersEnum::CREATED_AT->value][0],
                    $filters[OrderFiltersEnum::CREATED_AT->value][1] ?? Carbon::parse($filters[OrderFiltersEnum::CREATED_AT->value][0])->endOfDay()->format("Y-m-d H:i:s")
                ]);
            });
    }
}
