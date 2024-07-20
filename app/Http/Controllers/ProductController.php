<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Product\ProductExpandEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Enums\Product\ProductSortFieldsEnum;
use App\Exceptions\ProductNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductIndexRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SupplierService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function index(ProductIndexRequest $request): Response
    {
        $params = $request->validated();
        $params['expand'] = array_unique(array_merge($params['expand'] ?? [], [
            ProductExpandEnum::CATEGORY->value,
            ProductExpandEnum::SUPPLIER->value,
        ]));

        return Inertia::render(
            component: 'Product/Index',
            props: [
                'products' => $this->service->getAll($params),
                'filters'  => [
                    ProductFiltersEnum::NAME->value          => [
                        'label'       => ProductFiltersEnum::NAME->label(),
                        'placeholder' => 'Enter name.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[ProductFiltersEnum::NAME->value] ?? "",
                    ],
                    ProductFiltersEnum::PRODUCT_CODE->value  => [
                        'label'       => ProductFiltersEnum::PRODUCT_CODE->label(),
                        'placeholder' => 'Enter product code.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[ProductFiltersEnum::PRODUCT_CODE->value] ?? "",
                    ],
                    ProductFiltersEnum::CATEGORY_ID->value   => [
                        'label'       => ProductFiltersEnum::CATEGORY_ID->label(),
                        'placeholder' => 'Select category.',
                        'type'        => FilterFieldTypeEnum::SELECT->value,
                        'value'       => $request->validated()[ProductFiltersEnum::CATEGORY_ID->value] ?? "",
                    ],
                    ProductFiltersEnum::SUPPLIER_ID->value   => [
                        'label'       => ProductFiltersEnum::SUPPLIER_ID->label(),
                        'placeholder' => 'Select supplier.',
                        'type'        => FilterFieldTypeEnum::SELECT->value,
                        'value'       => $request->validated()[ProductFiltersEnum::SUPPLIER_ID->value] ?? "",
                    ],
                    ProductFiltersEnum::BUYING_PRICE->value  => [
                        'label'       => ProductFiltersEnum::BUYING_PRICE->label(),
                        'placeholder' => 'Enter buying price.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[ProductFiltersEnum::BUYING_PRICE->value] ?? "",
                    ],
                    ProductFiltersEnum::SELLING_PRICE->value => [
                        'label'       => ProductFiltersEnum::SELLING_PRICE->label(),
                        'placeholder' => 'Enter selling price.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[ProductFiltersEnum::SELLING_PRICE->value] ?? "",
                    ],
                    ProductFiltersEnum::BUYING_DATE->value   => [
                        'label'       => ProductFiltersEnum::BUYING_DATE->label(),
                        'placeholder' => 'Enter buying date.',
                        'type'        => FilterFieldTypeEnum::DATE_RANGE->value,
                        'value'       => $request->validated()[ProductFiltersEnum::BUYING_DATE->value] ?? "",
                    ],
                    ProductFiltersEnum::QUANTITY->value      => [
                        'label'       => ProductFiltersEnum::QUANTITY->label(),
                        'placeholder' => 'Enter quantity.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[ProductFiltersEnum::QUANTITY->value] ?? "",
                    ],
                    "sort_by"                                => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(ProductSortFieldsEnum::choices()),
                    ],
                    "sort_order"                             => [
                        'label'       => 'Sort order',
                        'placeholder' => 'Select a sort order',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_order'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SortOrderEnum::choices()),
                    ],
                    ProductFiltersEnum::CREATED_AT->value    => [
                        'label'       => ProductFiltersEnum::CREATED_AT->label(),
                        'placeholder' => 'Enter created at.',
                        'type'        => FilterFieldTypeEnum::DATE_RANGE->value,
                        'value'       => $request->validated()[ProductFiltersEnum::CREATED_AT->value] ?? "",
                    ],
                ],
            ]);
    }

    public function create(): Response
    {
        return Inertia::render(
            component: 'Product/Create'
        );
    }

    public function store(ProductCreateRequest $request): RedirectResponse
    {
        try {
            $this->service->create(
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Product created successfully.'
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Product creation failed!",
            ];

            Log::error("Something went wrong", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('flash', $flash);
    }

    public function update(ProductUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->service->update(
                id: $id,
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Product updated successfully.'
            ];
        } catch (ProductNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Product update failed!",
            ];

            Log::error("Something went wrong", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('flash', $flash);
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->service->delete(id: $id);
            $flash = [
                "message" => 'Product deleted successfully.'
            ];
        } catch (ProductNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Product deletion failed!",
            ];

            Log::error("Something went wrong", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('flash', $flash);
    }
}
