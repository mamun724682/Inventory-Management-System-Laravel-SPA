<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\FilterResourceEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Employee\EmployeeFieldsEnum;
use App\Enums\Salary\SalaryExpandEnum;
use App\Enums\Salary\SalaryFiltersEnum;
use App\Enums\Salary\SalarySortFieldsEnum;
use App\Exceptions\EmployeeNotFoundException;
use App\Exceptions\SalaryAlreadyPaidException;
use App\Exceptions\SalaryNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\Salary\SalaryCreateRequest;
use App\Http\Requests\Salary\SalaryIndexRequest;
use App\Http\Requests\Salary\SalaryUpdateRequest;
use App\Services\SalaryService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SalaryController extends Controller
{
    public function __construct(private readonly SalaryService $service)
    {
    }

    public function index(SalaryIndexRequest $request): Response
    {
        $params = $request->validated();
        $params['expand'] = [
            ...$params['expand'] ?? [],
            SalaryExpandEnum::EMPLOYEE->value,
        ];

        return Inertia::render(
            component: 'Salary/Index',
            props: [
                'salaries' => $this->service->getAll($params),
                'filters'  => [
                    SalaryFiltersEnum::EMPLOYEE_ID->value => [
                        'label'         => SalaryFiltersEnum::EMPLOYEE_ID->label(),
                        'placeholder'   => 'Select employee.',
                        'type'          => FilterFieldTypeEnum::SELECT->value,
                        'value'         => $request->validated()[SalaryFiltersEnum::EMPLOYEE_ID->value] ?? "",
                        "resource"      => FilterResourceEnum::EMPLOYEES->value,
                        'resourceLabel' => EmployeeFieldsEnum::NAME->value,
                    ],
                    SalaryFiltersEnum::AMOUNT->value      => [
                        'label'       => SalaryFiltersEnum::AMOUNT->label(),
                        'placeholder' => 'Enter amount.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[SalaryFiltersEnum::AMOUNT->value] ?? "",
                    ],
                    SalaryFiltersEnum::SALARY_DATE->value => [
                        'label'       => SalaryFiltersEnum::SALARY_DATE->label(),
                        'placeholder' => 'Enter salary month.',
                        'type'        => FilterFieldTypeEnum::MONTH->value,
                        'value'       => $request->validated()[SalaryFiltersEnum::SALARY_DATE->value] ?? "",
                    ],
                    "sort_by"                             => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SalarySortFieldsEnum::choices()),
                    ],
                    "sort_order"                          => [
                        'label'       => 'Sort order',
                        'placeholder' => 'Select a sort order',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_order'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SortOrderEnum::choices()),
                    ],
                    SalaryFiltersEnum::CREATED_AT->value  => [
                        'label'       => SalaryFiltersEnum::CREATED_AT->label(),
                        'placeholder' => 'Enter created at.',
                        'type'        => FilterFieldTypeEnum::DATETIME_RANGE->value,
                        'value'       => $request->validated()[SalaryFiltersEnum::CREATED_AT->value] ?? "",
                    ],
                ],
            ]);
    }

    public function store(SalaryCreateRequest $request): RedirectResponse
    {
        try {
            $this->service->create(
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Salary created successfully.'
            ];
        } catch (EmployeeNotFoundException|SalaryAlreadyPaidException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Salary creation failed!",
            ];

            Log::error("Salary creation failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('salaries.index')
            ->with('flash', $flash);
    }

    public function update(SalaryUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->service->update(
                id: $id,
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Salary updated successfully.'
            ];
        } catch (SalaryNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Salary update failed!",
            ];

            Log::error("Salary update failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('salaries.index')
            ->with('flash', $flash);
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->service->delete(id: $id);
            $flash = [
                "message" => 'Salary deleted successfully.'
            ];
        } catch (SalaryNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Salary deletion failed!",
            ];

            Log::error("Salary deletion failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('salaries.index')
            ->with('flash', $flash);
    }
}
