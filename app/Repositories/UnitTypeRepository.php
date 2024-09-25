<?php

namespace App\Repositories;

use App\Enums\UnitType\UnitTypeFieldsEnum;
use App\Enums\UnitType\UnitTypeFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\UnitType;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class UnitTypeRepository
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
     * @return UnitType|null
     */
    public function find(array $filters = [], array $expand = []): ?UnitType
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
            $unitType = UnitType::create($payload);
            DB::commit();
            return $unitType;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param UnitType $unitType
     * @param array $changes
     * @return UnitType
     * @throws Exception
     */
    public function update(UnitType $unitType, array $changes): UnitType
    {
        $attempt = 1;
        do {
            $updated = $unitType->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during unit type update");
        }

        return $unitType->refresh();
    }

    /**
     * @param UnitType $unitType
     * @return bool|null
     */
    public function delete(UnitType $unitType): ?bool
    {
        return $unitType->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return UnitType::query()
            ->when(isset($filters[UnitTypeFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(UnitTypeFieldsEnum::ID->value, $filters[UnitTypeFiltersEnum::ID->value]);
            })
            ->when(isset($filters[UnitTypeFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(UnitTypeFieldsEnum::NAME->value, "like", "%" . $filters[UnitTypeFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[UnitTypeFiltersEnum::SYMBOL->value]), function ($query) use ($filters) {
                $query->where(UnitTypeFieldsEnum::SYMBOL->value, $filters[UnitTypeFiltersEnum::SYMBOL->value]);
            })
            ->when(isset($filters[UnitTypeFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(UnitTypeFieldsEnum::CREATED_AT->value, $filters[UnitTypeFiltersEnum::CREATED_AT->value]);
            });
    }
}
