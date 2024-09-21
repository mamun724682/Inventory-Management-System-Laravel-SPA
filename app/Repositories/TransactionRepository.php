<?php

namespace App\Repositories;

use App\Enums\Transaction\TransactionFieldsEnum;
use App\Enums\Transaction\TransactionFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Transaction;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TransactionRepository
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
     * @return Transaction|null
     */
    public function find(array $filters = [], array $expand = []): ?Transaction
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
            $transaction = Transaction::create($payload);
            DB::commit();
            return $transaction;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Transaction $transaction
     * @param array $changes
     * @return Transaction
     * @throws Exception
     */
    public function update(Transaction $transaction, array $changes): Transaction
    {
        $attempt = 1;
        do {
            $updated = $transaction->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during transaction update");
        }

        return $transaction->refresh();
    }

    /**
     * @param Transaction $transaction
     * @return bool|null
     */
    public function delete(Transaction $transaction): ?bool
    {
        return $transaction->delete();
    }

    private function getQuery(array $filters)
    {
        return Transaction::query()
            ->when(isset($filters[TransactionFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(TransactionFieldsEnum::ID->value, $filters[TransactionFiltersEnum::ID->value]);
            })
            ->when(isset($filters[TransactionFiltersEnum::ORDER_ID->value]), function ($query) use ($filters) {
                $query->where(TransactionFieldsEnum::ORDER_ID->value, $filters[TransactionFiltersEnum::ORDER_ID->value]);
            })
            ->when(isset($filters[TransactionFiltersEnum::TRANSACTION_NUMBER->value]), function ($query) use ($filters) {
                $query->where(TransactionFieldsEnum::TRANSACTION_NUMBER->value, $filters[TransactionFiltersEnum::TRANSACTION_NUMBER->value]);
            })
            ->when(isset($filters[TransactionFiltersEnum::PAID_THROUGH->value]), function ($query) use ($filters) {
                $query->where(TransactionFieldsEnum::PAID_THROUGH->value, $filters[TransactionFiltersEnum::PAID_THROUGH->value]);
            })
            ->when(isset($filters[TransactionFiltersEnum::AMOUNT->value]), function ($query) use ($filters) {
                $query->whereBetween(TransactionFieldsEnum::AMOUNT->value, $filters[TransactionFiltersEnum::AMOUNT->value]);
            })
            ->when(isset($filters[TransactionFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(TransactionFieldsEnum::CREATED_AT->value, $filters[TransactionFiltersEnum::CREATED_AT->value]);
            });
    }
}
