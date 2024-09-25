<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\UnitType\UnitTypeFiltersEnum;
use App\Enums\UnitType\UnitTypeSortFieldsEnum;
use App\Exceptions\UnitTypeNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\UnitType\UnitTypeCreateRequest;
use App\Http\Requests\UnitType\UnitTypeIndexRequest;
use App\Http\Requests\UnitType\UnitTypeUpdateRequest;
use App\Services\UnitTypeService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class UnitTypeController extends Controller
{
    public function __construct(private readonly UnitTypeService $service)
    {
    }

    public function index(UnitTypeIndexRequest $request)
    {
        if ($request->inertia == "disabled") {
            $query = $request->validated();
            $query["sort_by"] = UnitTypeSortFieldsEnum::NAME->value;
            return $this->service->getAll($query);
        }

        return Inertia::render(
            component: 'UnitType/Index',
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

    public function store(UnitTypeCreateRequest $request): RedirectResponse
    {
        try {
            $this->service->create(
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Unit type created successfully.'
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Unit type creation failed!",
            ];

            Log::error("Unit type creation failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('unit-types.index')
            ->with('flash', $flash);
    }

    public function update(UnitTypeUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->service->update(
                id: $id,
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Unit type updated successfully.'
            ];
        } catch (UnitTypeNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Unit type update failed!",
            ];

            Log::error("Unit type update failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('unit-types.index')
            ->with('flash', $flash);
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->service->delete(id: $id);
            $flash = [
                "message" => 'Unit type deleted successfully.'
            ];
        } catch (UnitTypeNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Unit type deletion failed!",
            ];

            Log::error("Unit type deletion failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('unit-types.index')
            ->with('flash', $flash);
    }
}
