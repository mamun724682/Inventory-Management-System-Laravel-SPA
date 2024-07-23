<?php

namespace App\Services;

use App\Enums\Core\SortOrderEnum;
use App\Enums\Salary\SalaryFieldsEnum;
use App\Enums\Salary\SalaryFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Exceptions\EmployeeNotFoundException;
use App\Exceptions\SalaryAlreadyPaidException;
use App\Exceptions\SalaryNotFoundException;
use App\Helpers\ArrayHelper;
use App\Helpers\BaseHelper;
use App\Models\Salary;
use App\Repositories\SalaryRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SalaryService
{
    public function __construct(
        private readonly SalaryRepository $repository,
        private readonly EmployeeService  $employeeService
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
            filters: ArrayHelper::getFiltersValues($queryParameters, SalaryFiltersEnum::values()),
            fields: $queryParameters["fields"] ?? [],
            expand: $queryParameters["expand"] ?? [],
            sortBy: $queryParameters["sort_by"] ?? SalaryFieldsEnum::CREATED_AT->value,
            sortOrder: $queryParameters["sort_order"] ?? SortOrderEnum::DESC->value,
        );
    }

    /**
     * @param int $id
     * @param array $expands
     * @return Salary|null
     * @throws SalaryNotFoundException
     */
    public function findByIdOrFail(int $id, array $expands = []): ?Salary
    {
        $salary = $this->repository->find(
            filters: [
                SalaryFiltersEnum::ID->value => $id
            ],
            expand: $expands
        );

        if (!$salary) {
            throw new SalaryNotFoundException('Salary not found by the given id.');
        }

        return $salary;
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws DBCommitException
     * @throws EmployeeNotFoundException
     * @throws SalaryAlreadyPaidException
     */
    public function create(array $payload): mixed
    {
        $employee = $this->employeeService->findByIdOrFail(id: $payload["employee_id"]);

        // Check salary paid for current month
        $salary = $this->repository->exists(
            filters: [
                SalaryFiltersEnum::EMPLOYEE_ID->value => $payload["employee_id"],
                SalaryFiltersEnum::SALARY_DATE->value => $payload["salary_date"],
            ]
        );

        if ($salary) {
            throw new SalaryAlreadyPaidException('Salary already paid for the current month.');
        }

        $processPayload = [
            SalaryFieldsEnum::EMPLOYEE_ID->value => $payload[SalaryFieldsEnum::EMPLOYEE_ID->value],
            SalaryFieldsEnum::AMOUNT->value      => $employee->salary,
            SalaryFieldsEnum::SALARY_DATE->value => $payload[SalaryFieldsEnum::SALARY_DATE->value],
        ];

        return $this->repository->create(payload: $processPayload);
    }

    /**
     * @param int $id
     * @param array $payload
     * @return Salary
     * @throws SalaryNotFoundException
     * @throws Exception
     */
    public function update(int $id, array $payload): Salary
    {
        $salary = $this->findByIdOrFail(id: $id);

        $processPayload = [
            SalaryFieldsEnum::EMPLOYEE_ID->value => $payload[SalaryFieldsEnum::EMPLOYEE_ID->value] ?? $salary->employee_id,
            SalaryFieldsEnum::AMOUNT->value      => $payload[SalaryFieldsEnum::AMOUNT->value] ?? $salary->amount,
            SalaryFieldsEnum::SALARY_DATE->value => $payload[SalaryFieldsEnum::SALARY_DATE->value] ?? $salary->salary_date,
        ];

        return $this->repository->update(
            salary: $salary,
            changes: $processPayload
        );
    }

    /**
     * @param int $id
     * @return bool|null
     * @throws SalaryNotFoundException
     */
    public function delete(int $id): ?bool
    {
        $salary = $this->findByIdOrFail(id: $id);
        return $this->repository->delete(salary: $salary);
    }
}
