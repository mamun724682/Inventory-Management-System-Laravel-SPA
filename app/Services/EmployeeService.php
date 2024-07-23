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
    public function __construct(
        private readonly EmployeeRepository $repository,
        private readonly FileManagerService $fileManagerService
    )
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
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     */
    public function create(array $payload): mixed
    {
        $photo = null;
        if (isset($payload['photo'])) {
            $photo = $this->fileManagerService->uploadFile(
                file: $payload['photo'],
                uploadPath: Employee::PHOTO_PATH
            );
        }

        $processPayload = [
            EmployeeFieldsEnum::NAME->value         => $payload[EmployeeFieldsEnum::NAME->value],
            EmployeeFieldsEnum::EMAIL->value        => $payload[EmployeeFieldsEnum::EMAIL->value],
            EmployeeFieldsEnum::PHONE->value        => $payload[EmployeeFieldsEnum::PHONE->value],
            EmployeeFieldsEnum::DESIGNATION->value  => $payload[EmployeeFieldsEnum::DESIGNATION->value],
            EmployeeFieldsEnum::SALARY->value       => $payload[EmployeeFieldsEnum::SALARY->value],
            EmployeeFieldsEnum::ADDRESS->value      => $payload[EmployeeFieldsEnum::ADDRESS->value],
            EmployeeFieldsEnum::NID->value          => $payload[EmployeeFieldsEnum::NID->value],
            EmployeeFieldsEnum::JOINING_DATE->value => $payload[EmployeeFieldsEnum::JOINING_DATE->value],
            EmployeeFieldsEnum::PHOTO->value        => $photo,
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

        $photo = $employee->getRawOriginal(EmployeeFieldsEnum::PHOTO->value);
        if (isset($payload['photo'])) {
            $photo = $this->fileManagerService->uploadFile(
                file: $payload['photo'],
                uploadPath: Employee::PHOTO_PATH,
                deleteFileName: $photo
            );
        }

        $processPayload = [
            EmployeeFieldsEnum::NAME->value         => $payload[EmployeeFieldsEnum::NAME->value] ?? $employee->name,
            EmployeeFieldsEnum::EMAIL->value        => $payload[EmployeeFieldsEnum::EMAIL->value] ?? $employee->email,
            EmployeeFieldsEnum::PHONE->value        => $payload[EmployeeFieldsEnum::PHONE->value] ?? $employee->phone,
            EmployeeFieldsEnum::DESIGNATION->value  => $payload[EmployeeFieldsEnum::DESIGNATION->value] ?? $employee->designation,
            EmployeeFieldsEnum::SALARY->value       => $payload[EmployeeFieldsEnum::SALARY->value] ?? $employee->salary,
            EmployeeFieldsEnum::ADDRESS->value      => $payload[EmployeeFieldsEnum::ADDRESS->value] ?? $employee->address,
            EmployeeFieldsEnum::NID->value          => $payload[EmployeeFieldsEnum::NID->value] ?? $employee->nid,
            EmployeeFieldsEnum::JOINING_DATE->value => $payload[EmployeeFieldsEnum::JOINING_DATE->value] ?? $employee->joining_date,
            EmployeeFieldsEnum::PHOTO->value        => $photo,
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
        $photo = $employee->getRawOriginal(EmployeeFieldsEnum::PHOTO->value);
        if ($photo) {
            $this->fileManagerService->delete(
                fileName: $photo,
                path: Employee::PHOTO_PATH,
            );
        }

        return $this->repository->delete(employee: $employee);
    }
}
