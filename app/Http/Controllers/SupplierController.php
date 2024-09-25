<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Supplier\SupplierSortFieldsEnum;
use App\Exceptions\SupplierNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\Supplier\SupplierCreateRequest;
use App\Http\Requests\Supplier\SupplierIndexRequest;
use App\Http\Requests\Supplier\SupplierUpdateRequest;
use App\Services\SupplierService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SupplierController extends Controller
{
    public function __construct(private readonly SupplierService $service)
    {
    }

    public function index(SupplierIndexRequest $request): LengthAwarePaginator|Response
    {
        if ($request->inertia == "disabled"){
            $query = $request->validated();
            $query["sort_by"] = SupplierSortFieldsEnum::NAME->value;
            return $this->service->getAll($query);
        }

        return Inertia::render(
            component: 'Supplier/Index',
            props: [
                'suppliers' => $this->service->getAll($request->validated()),
                'filters'    => [
                    "name"       => [
                        'label'       => 'Name',
                        'placeholder' => 'Enter name.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()['name'] ?? "",
                    ],
                    "email"      => [
                        'label'       => 'Email',
                        'placeholder' => 'Enter email.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()['email'] ?? "",
                    ],
                    "phone"      => [
                        'label'       => 'Phone',
                        'placeholder' => 'Enter phone.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()['phone'] ?? "",
                    ],
                    "shop_name"  => [
                        'label'       => 'Shop Name',
                        'placeholder' => 'Enter shop name.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()['shop_name'] ?? "",
                    ],
                    "sort_by"    => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SupplierSortFieldsEnum::choices()),
                    ],
                    "sort_order" => [
                        'label'       => 'Sort order',
                        'placeholder' => 'Select a sort order',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_order'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SortOrderEnum::choices()),
                    ]
                ],
            ]);
    }

    public function store(SupplierCreateRequest $request): RedirectResponse
    {
        try {
            $this->service->create(
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Supplier created successfully.'
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Supplier creation failed!",
            ];

            Log::error("Supplier creation failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('suppliers.index')
            ->with('flash', $flash);
    }

    public function update(SupplierUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->service->update(
                id: $id,
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Supplier updated successfully.'
            ];
        } catch (SupplierNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Supplier update failed!",
            ];

            Log::error("Supplier update failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('suppliers.index')
            ->with('flash', $flash);
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->service->delete(id: $id);
            $flash = [
                "message" => 'Supplier deleted successfully.'
            ];
        } catch (SupplierNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Supplier deletion failed!",
            ];

            Log::error("Supplier deletion failed!", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('suppliers.index')
            ->with('flash', $flash);
    }
}
