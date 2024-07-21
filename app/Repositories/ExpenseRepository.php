<?php

namespace App\Repositories;

use App\Enums\Expense\ExpenseFieldsEnum;
use App\Enums\Expense\ExpenseFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Expense;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ExpenseRepository
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
     * @return Expense|null
     */
    public function find(array $filters = [], array $expand = []): ?Expense
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
            $expense = Expense::create($payload);
            DB::commit();
            return $expense;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Expense $expense
     * @param array $changes
     * @return Expense
     * @throws Exception
     */
    public function update(Expense $expense, array $changes): Expense
    {
        $attempt = 1;
        do {
            $updated = $expense->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during expense update");
        }

        return $expense->refresh();
    }

    /**
     * @param Expense $expense
     * @return bool|null
     */
    public function delete(Expense $expense): ?bool
    {
        return $expense->delete();
    }

    private function getQuery(array $filters)
    {
        return Expense::query()
            ->when(isset($filters[ExpenseFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(ExpenseFieldsEnum::ID->value, $filters[ExpenseFiltersEnum::ID->value]);
            })
            ->when(isset($filters[ExpenseFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(ExpenseFieldsEnum::NAME->value, "like", "%" . $filters[ExpenseFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[ExpenseFiltersEnum::AMOUNT->value]), function ($query) use ($filters) {
                $query->whereBetween(ExpenseFieldsEnum::AMOUNT->value, $filters[ExpenseFiltersEnum::AMOUNT->value]);
            })
            ->when(isset($filters[ExpenseFiltersEnum::EXPENSE_DATE->value]), function ($query) use ($filters) {
                $query->whereBetween(ExpenseFieldsEnum::EXPENSE_DATE->value, $filters[ExpenseFiltersEnum::EXPENSE_DATE->value]);
            })
            ->when(isset($filters[ExpenseFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(ExpenseFieldsEnum::CREATED_AT->value, $filters[ExpenseFiltersEnum::CREATED_AT->value]);
            });
    }
}
