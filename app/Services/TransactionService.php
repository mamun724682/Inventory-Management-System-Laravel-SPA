<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Transaction\TransactionFieldsEnum;
use App\Enums\Transaction\TransactionFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Repositories\TransactionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TransactionService
{
    public function __construct(private readonly TransactionRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, TransactionFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? TransactionFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     */
    public function create(array $payload): mixed
    {
        $processPayload = [
            TransactionFieldsEnum::TRANSACTION_NUMBER->value => 'TRX-' . Str::random(5),
            TransactionFieldsEnum::ORDER_ID->value           => $payload[TransactionFieldsEnum::ORDER_ID->value],
            TransactionFieldsEnum::AMOUNT->value             => $payload[TransactionFieldsEnum::AMOUNT->value],
            TransactionFieldsEnum::PAID_THROUGH->value       => $payload[TransactionFieldsEnum::PAID_THROUGH->value],
        ];

        return $this->repository->create(payload: $processPayload);
    }
}
