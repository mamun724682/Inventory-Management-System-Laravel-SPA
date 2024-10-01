<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\User\UserFiltersEnum;
use App\Enums\User\UserSortFieldsEnum;
use App\Helpers\BaseHelper;
use App\Http\Requests\User\UserIndexRequest;
use App\Services\UserService;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service)
    {
    }

    public function index(UserIndexRequest $request)
    {
        if ($request->inertia == "disabled") {
            $query = $request->validated();
            $query["sort_by"] = UserSortFieldsEnum::NAME->value;
            return $this->service->getAll($query);
        }

        return Inertia::render(
            component: 'User/Index',
            props: [
                'users' => $this->service->getAll($request->validated()),
                'filters'   => [
                    UserFiltersEnum::NAME->value  => [
                        'label'       => UserFiltersEnum::NAME->label(),
                        'placeholder' => 'Enter name.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[UserFiltersEnum::NAME->value] ?? "",
                    ],
                    UserFiltersEnum::EMAIL->value => [
                        'label'       => UserFiltersEnum::EMAIL->label(),
                        'placeholder' => 'Enter email.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[UserFiltersEnum::EMAIL->value] ?? "",
                    ],
                    "sort_by"                     => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(UserSortFieldsEnum::choices()),
                    ],
                    "sort_order"                  => [
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
