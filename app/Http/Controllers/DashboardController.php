<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\UnitType\UnitTypeFiltersEnum;
use App\Enums\UnitType\UnitTypeSortFieldsEnum;
use App\Helpers\BaseHelper;
use App\Http\Requests\UnitType\UnitTypeIndexRequest;
use App\Services\UnitTypeService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly UnitTypeService $service)
    {
    }

    public function __invoke(UnitTypeIndexRequest $request)
    {
        return Inertia::render(
            component: 'Dashboard',
            props: [
                'unitTypes' => $this->service->getAll($request->validated()),
                'filters'   => [
                    UnitTypeFiltersEnum::NAME->value   => [
                        'label'       => UnitTypeFiltersEnum::NAME->label(),
                        'placeholder' => 'Enter name.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[UnitTypeFiltersEnum::NAME->value] ?? "",
                    ],
                    UnitTypeFiltersEnum::SYMBOL->value => [
                        'label'       => UnitTypeFiltersEnum::SYMBOL->label(),
                        'placeholder' => 'Enter symbol.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[UnitTypeFiltersEnum::SYMBOL->value] ?? "",
                    ],
                    "sort_by"                          => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(UnitTypeSortFieldsEnum::choices()),
                    ],
                    "sort_order"                       => [
                        'label'       => 'Sort order',
                        'placeholder' => 'Select a sort order',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_order'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SortOrderEnum::choices()),
                    ]
                ],
            ]);
    }
}
