<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Employee\EmployeeFieldsEnum;
use App\Enums\Employee\EmployeeFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\EmployeeNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function __construct(private readonly EmployeeRepository $repository)
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
            filters: ArrayHelper::getFiltersValues($queryParameters, EmployeeFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? EmployeeFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Employee|null
     * @throws EmployeeNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Employee
    {
        $employee = $this->repository->find(
            filters: [
                EmployeeFiltersEnum::ID->value => $id
            ],
            expand: $expands
        );

        if (!$employee) {
            throw new EmployeeNotFoundException('Employee not found by the given id.');
        }

        return $employee;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isIdExists(int $id): bool
    {
        return $this->repository->exists(filters: [
            EmployeeFieldsEnum::ID->value => $id
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
            EmployeeFieldsEnum::NAME->value => $payload[EmployeeFieldsEnum::NAME->value],
        ];

        return $this->repository->create(payload: $processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return Employee
     * @throws EmployeeNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): Employee
    {
        $employee = $this->findByIdOrFail(id: $id);

        $processPayload = [
            EmployeeFieldsEnum::NAME->value => $payload[EmployeeFieldsEnum::NAME->value],
        ];

        return $this->repository->update(
            employee: $employee,
            changes: $processPayload
        );
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws EmployeeNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $employee = $this->findByIdOrFail(id: $id);
        return $this->repository->delete(employee: $employee);
    }
}
