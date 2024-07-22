<?php

namespace App\Repositories;

use App\Enums\Salary\SalaryFieldsEnum;
use App\Enums\Salary\SalaryFiltersEnum;
use App\Exceptions\DBCommitException;
use App\Models\Salary;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SalaryRepository
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
     * @return Salary|null
     */
    public function find(array $filters = [], array $expand = []): ?Salary
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
            $salary = Salary::create($payload);
            DB::commit();
            return $salary;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new DBCommitException($exception);
        }
    }

    /**
     * @param Salary $salary
     * @param array $changes
     * @return Salary
     * @throws Exception
     */
    public function update(Salary $salary, array $changes): Salary
    {
        $attempt = 1;
        do {
            $updated = $salary->update($changes);
            $attempt++;
        } while (!$updated && $attempt <= self::MAX_RETRY);

        if (!$updated && $attempt > self::MAX_RETRY) {
            throw new Exception("Max retry exceeded during salary update");
        }

        return $salary->refresh();
    }

    /**
     * @param Salary $salary
     * @return bool|null
     */
    public function delete(Salary $salary): ?bool
    {
        return $salary->delete();
    }

    private function getQuery(array $filters)
    {
        return Salary::query()
            ->when(isset($filters[SalaryFiltersEnum::ID->value]), function ($query) use ($filters) {
                $query->where(SalaryFieldsEnum::ID->value, $filters[SalaryFiltersEnum::ID->value]);
            })
            ->when(isset($filters[SalaryFiltersEnum::EMPLOYEE_ID->value]), function ($query) use ($filters) {
                $query->where(SalaryFieldsEnum::EMPLOYEE_ID->value, $filters[SalaryFiltersEnum::EMPLOYEE_ID->value]);
            })
            ->when(isset($filters[SalaryFiltersEnum::AMOUNT->value]), function ($query) use ($filters) {
                $query->whereBetween(SalaryFieldsEnum::AMOUNT->value, $filters[SalaryFiltersEnum::AMOUNT->value]);
            })
            ->when(isset($filters[SalaryFiltersEnum::SALARY_DATE->value]), function ($query) use ($filters) {
                $salaryDate = Carbon::parse($filters[SalaryFiltersEnum::SALARY_DATE->value]);
                $query->whereMonth(SalaryFieldsEnum::SALARY_DATE->value, $salaryDate->get("month"))
                    ->whereYear(SalaryFieldsEnum::SALARY_DATE->value, $salaryDate->get("year"));
            })
            ->when(isset($filters[SalaryFiltersEnum::CREATED_AT->value]), function ($query) use ($filters) {
                $query->whereBetween(SalaryFieldsEnum::CREATED_AT->value, $filters[SalaryFiltersEnum::CREATED_AT->value]);
            });
    }
}
