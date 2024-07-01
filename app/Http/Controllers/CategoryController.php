<?php

namespace App\Http\Controllers;

use App\Enums\Category\CategorySortFieldsEnum;
use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\SortOrderEnum;
use App\Helpers\BaseHelper;
use App\Http\Requests\Category\CategoryIndexRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service)
    {
    }

    public function index(CategoryIndexRequest $request): Response
    {
        return Inertia::render(
            component: 'Category/Index',
            props: [
                'categories' => $this->service->getAll($request->validated()),
                'filters'    => [
                    "name"       => [
                        'label'       => 'Name',
                        'placeholder' => 'Enter name.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()['name'] ?? "",
                    ],
                    "sort_by"    => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(CategorySortFieldsEnum::choices()),
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
}
