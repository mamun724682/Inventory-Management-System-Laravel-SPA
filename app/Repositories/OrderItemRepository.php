<?php

namespace App\Repositories;

use App\Enums\OrderItem\OrderItemFieldsEnum;
use App\Enums\OrderItem\OrderItemFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\OrderItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class OrderItemRepository
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
     * @return OrderItem|null
     */
    public function find(array $filters = [], array $expand = []): ?OrderItem
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
            $orderItem = OrderItem::create($payload);
            DB::commit();
            return $orderItem;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param array $payloads
     * @return void
     * @throws DBCommitException
     */
    public function insert(array $payloads): void
    {
        try {
            DB::beginTransaction();
            OrderItem::query()->insert($payloads);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param array $changes
     * @return OrderItem
     * @throws Exception
     */
    public function update(OrderItem $orderItem, array $changes): OrderItem
    {
        $attempt = 1;
        do {
            $updated = $orderItem->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during order item update");
        }

        return $orderItem->refresh();
    }

    /**
     * @param OrderItem $orderItem
     * @return bool|null
     */
    public function delete(OrderItem $orderItem): ?bool
    {
        return $orderItem->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return OrderItem::query()
            ->when(isset($filters[OrderItemFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(OrderItemFieldsEnum::ID->value, $filters[OrderItemFiltersEnum::ID->value]);
            })
            ->when(isset($filters[OrderItemFiltersEnum::ORDER_ID->value]), function ($query) use ($filters) {
                $query->where(OrderItemFieldsEnum::ORDER_ID->value, $filters[OrderItemFiltersEnum::ORDER_ID->value]);
            })
            ->when(isset($filters[OrderItemFiltersEnum::PRODUCT_ID->value]), function ($query) use ($filters) {
                $query->where(OrderItemFieldsEnum::PRODUCT_ID->value, $filters[OrderItemFiltersEnum::PRODUCT_ID->value]);
            })
            ->when(isset($filters[OrderItemFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(OrderItemFieldsEnum::CREATED_AT->value, [
                    $filters[OrderItemFiltersEnum::CREATED_AT->value][0],
                    $filters[OrderItemFiltersEnum::CREATED_AT->value][1] ?? Carbon::parse($filters[OrderItemFiltersEnum::CREATED_AT->value][0])->endOfDay()->format("Y-m-d H:i:s")
                ]);
            });
    }
}
