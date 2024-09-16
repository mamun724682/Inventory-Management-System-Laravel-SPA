<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\OrderItem\OrderItemFieldsEnum;
use App\Enums\OrderItem\OrderItemFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\OrderItemNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderItemService
{
    public function __construct(private readonly OrderItemRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, OrderItemFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? OrderItemFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return OrderItem|null
     * @throws OrderItemNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?OrderItem
    {
        $orderItem = $this->repository->find([
            OrderItemFiltersEnum::ID->value => $id
        ], $expands);

        if (!$orderItem) {
            throw new OrderItemNotFoundException('Order item not found by the given id.');
        }

        return $orderItem;
    }

    /**
     * @param array $payloads
     * @param int $orderId
     * @return void
     * @throws DBCommitException
     */
    public function insert(array $payloads, int $orderId): void
    {
        $processPayloads = [];
        foreach ($payloads as $payload) {
            $processPayloads[] = [
                OrderItemFieldsEnum::ORDER_ID->value     => $orderId,
                OrderItemFieldsEnum::PRODUCT_ID->value   => $payload[OrderItemFieldsEnum::PRODUCT_ID->value],
                OrderItemFieldsEnum::PRODUCT_JSON->value => json_encode($payload[OrderItemFieldsEnum::PRODUCT_JSON->value]),
                OrderItemFieldsEnum::QUANTITY->value     => $payload[OrderItemFieldsEnum::QUANTITY->value],
            ];
        }

        $this->repository->insert($processPayloads);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return OrderItem
     * @throws OrderItemNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): OrderItem
    {
        $orderItem = $this->findByIdOrFail($id);

        $processPayload = [
            OrderItemFieldsEnum::QUANTITY->value     => $payload[OrderItemFieldsEnum::QUANTITY->value],
        ];

        return $this->repository->update($orderItem, $processPayload);
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws OrderItemNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $orderItem = $this->findByIdOrFail($id);
        return $this->repository->delete($orderItem);
    }
}
