<?php

namespace App\Services;

use App\Enums\Category\CategoryFieldsEnum;
use App\Enums\Category\CategoryFiltersEnum;
use App\Enums\Core\SortOrderEnum;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DBCommitException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(private readonly CategoryRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, CategoryFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? CategoryFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Category|null
     * @throws CategoryNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Category
    {
        $category = $this->repository->find([
            CategoryFiltersEnum::ID->value => $id
        ], $expands);

        if (!$category) {
            throw new CategoryNotFoundException('Category not found by the given id.');
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
            CategoryFieldsEnum::ID->value => $id
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
            CategoryFieldsEnum::NAME->value => $payload[CategoryFieldsEnum::NAME->value],
        ];

        return $this->repository->create($processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return Category
     * @throws CategoryNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): Category
    {
        $category = $this->findByIdOrFail($id);

        $processPayload = [
            CategoryFieldsEnum::NAME->value => $payload[CategoryFieldsEnum::NAME->value],
        ];

        return $this->repository->update($category, $processPayload);
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws CategoryNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $category = $this->findByIdOrFail($id);

        // Todo: prevent delete for available products

        return $this->repository->delete($category);
    }
}
