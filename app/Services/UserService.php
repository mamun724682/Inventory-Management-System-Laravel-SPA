<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\User\UserFieldsEnum;
use App\Enums\User\UserFiltersEnum;
use App\Exceptions\UserNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(private readonly UserRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, UserFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? UserFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return User|null
     * @throws UserNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?User
    {
        $category = $this->repository->find([
            UserFiltersEnum::ID->value => $id
        ], $expands);

        if (!$category) {
            throw new UserNotFoundException('User not found by the given id.');
        }

        return $category;
    }
}
