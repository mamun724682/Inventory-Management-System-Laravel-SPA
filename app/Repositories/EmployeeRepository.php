<?php

namespace App\Repositories;

use App\Enums\Employee\EmployeeFieldsEnum;
use App\Enums\Employee\EmployeeFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Employee;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderWhenProxy;

class EmployeeRepository
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
     * @return Employee|null
     */
    public function find(array $filters = [], array $expand = []): ?Employee
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
            $employee = Employee::create($payload);
            DB::commit();
            return $employee;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Employee $employee
     * @param array $changes
     * @return Employee
     * @throws Exception
     */
    public function update(Employee $employee, array $changes): Employee
    {
        $attempt = 1;
        do {
            $updated = $employee->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during employee update");
        }

        return $employee->refresh();
    }

    /**
     * @param Employee $employee
     * @return bool|null
     */
    public function delete(Employee $employee): ?bool
    {
        return $employee->delete();
    }

    /**
     * @param array $filters
     * @return Builder|HigherOrderWhenProxy
     */
    private function getQuery(array $filters): Builder|HigherOrderWhenProxy
    {
        return Employee::query()
            ->when(isset($filters[EmployeeFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(EmployeeFieldsEnum::ID->value, $filters[EmployeeFiltersEnum::ID->value]);
            })
            ->when(isset($filters[EmployeeFiltersEnum::NAME->value]), function ($query) use ($filters) {
                $query->where(EmployeeFieldsEnum::NAME->value, "like", "%" . $filters[EmployeeFiltersEnum::NAME->value] . "%");
            })
            ->when(isset($filters[EmployeeFiltersEnum::EMAIL->value]), function ($query) use ($filters) {
                $query->where(EmployeeFieldsEnum::EMAIL->value, $filters[EmployeeFiltersEnum::EMAIL->value]);
            })
            ->when(isset($filters[EmployeeFiltersEnum::PHONE->value]), function ($query) use ($filters) {
                $query->where(EmployeeFieldsEnum::PHONE->value, $filters[EmployeeFiltersEnum::PHONE->value]);
            })
            ->when(isset($filters[EmployeeFiltersEnum::DESIGNATION->value]), function ($query) use ($filters) {
                $query->where(EmployeeFieldsEnum::DESIGNATION->value, "like", "%" . $filters[EmployeeFiltersEnum::DESIGNATION->value] . "%");
            })
            ->when(isset($filters[EmployeeFiltersEnum::NID->value]), function ($query) use ($filters) {
                $query->where(EmployeeFieldsEnum::NID->value, $filters[EmployeeFiltersEnum::NID->value]);
            })
            ->when(isset($filters[EmployeeFiltersEnum::SALARY->value]), function ($query) use ($filters) {
                $query->whereBetween(EmployeeFieldsEnum::SALARY->value, $filters[EmployeeFiltersEnum::SALARY->value]);
            })
            ->when(isset($filters[EmployeeFiltersEnum::JOINING_DATE->value]), function ($query) use ($filters) {
                $query->whereBetween(EmployeeFieldsEnum::JOINING_DATE->value, $filters[EmployeeFiltersEnum::JOINING_DATE->value]);
            })
            ->when(isset($filters[EmployeeFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(EmployeeFieldsEnum::CREATED_AT->value, $filters[EmployeeFiltersEnum::CREATED_AT->value]);
            });
    }
}
