<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\UnitType\UnitTypeFieldsEnum;
use App\Enums\UnitType\UnitTypeFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\UnitTypeNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\UnitType;
use App\Repositories\UnitTypeRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UnitTypeService
{
    public function __construct(private readonly UnitTypeRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, UnitTypeFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? UnitTypeFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return UnitType|null
     * @throws UnitTypeNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?UnitType
    {
        $category = $this->repository->find([
            UnitTypeFiltersEnum::ID->value => $id
        ], $expands);

        if (!$category) {
            throw new UnitTypeNotFoundException('UnitType not found by the given id.');
        }

        return $category;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isIdExists(int $id): bool
    {
        return $this->repository->exists([
            UnitTypeFieldsEnum::ID->value => $id
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
            UnitTypeFieldsEnum::NAME->value   => $payload[UnitTypeFieldsEnum::NAME->value],
            UnitTypeFieldsEnum::SYMBOL->value => $payload[UnitTypeFieldsEnum::SYMBOL->value],
        ];

        return $this->repository->create($processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return UnitType
     * @throws UnitTypeNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): UnitType
    {
        $category = $this->findByIdOrFail($id);

        $processPayload = [
            UnitTypeFieldsEnum::NAME->value   => $payload[UnitTypeFieldsEnum::NAME->value],
            UnitTypeFieldsEnum::SYMBOL->value => $payload[UnitTypeFieldsEnum::SYMBOL->value],
        ];

        return $this->repository->update($category, $processPayload);
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws UnitTypeNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $category = $this->findByIdOrFail($id);
        return $this->repository->delete($category);
    }
}
