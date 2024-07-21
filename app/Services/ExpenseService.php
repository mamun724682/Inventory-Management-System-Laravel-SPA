<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Expense\ExpenseFieldsEnum;
use App\Enums\Expense\ExpenseFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\ExpenseNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseService
{
    public function __construct(private readonly ExpenseRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, ExpenseFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? ExpenseFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Expense|null
     * @throws ExpenseNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Expense
    {
        $expense = $this->repository->find(
            filters: [
                ExpenseFiltersEnum::ID->value => $id
            ],
            expand: $expands
        );

        if (!$expense) {
            throw new ExpenseNotFoundException('Expense not found by the given id.');
        }

        return $expense;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isIdExists(int $id): bool
    {
        return $this->repository->exists(
            filters: [
                ExpenseFieldsEnum::ID->value => $id
            ]);
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     */
    public function create(array $payload): mixed
    {
        $processPayload = [
            ExpenseFieldsEnum::NAME->value         => $payload[ExpenseFieldsEnum::NAME->value],
            ExpenseFieldsEnum::DESCRIPTION->value  => $payload[ExpenseFieldsEnum::DESCRIPTION->value],
            ExpenseFieldsEnum::AMOUNT->value       => $payload[ExpenseFieldsEnum::AMOUNT->value],
            ExpenseFieldsEnum::EXPENSE_DATE->value => $payload[ExpenseFieldsEnum::EXPENSE_DATE->value],
        ];

        return $this->repository->create(payload: $processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return Expense
     * @throws ExpenseNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): Expense
    {
        $expense = $this->findByIdOrFail(id: $id);

        $processPayload = [
            ExpenseFieldsEnum::NAME->value         => $payload[ExpenseFieldsEnum::NAME->value] ?? $expense->name,
            ExpenseFieldsEnum::DESCRIPTION->value  => $payload[ExpenseFieldsEnum::DESCRIPTION->value] ?? $expense->description,
            ExpenseFieldsEnum::AMOUNT->value       => $payload[ExpenseFieldsEnum::AMOUNT->value] ?? $expense->amount,
            ExpenseFieldsEnum::EXPENSE_DATE->value => $payload[ExpenseFieldsEnum::EXPENSE_DATE->value] ?? $expense->expense_date,
        ];

        return $this->repository->update(
            expense: $expense,
            changes: $processPayload
        );
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws ExpenseNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $expense = $this->findByIdOrFail(id: $id);
        return $this->repository->delete(expense: $expense);
    }
}
