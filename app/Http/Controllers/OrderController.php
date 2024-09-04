<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\FilterResourceEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Product\ProductExpandEnum;
use App\Enums\Product\ProductFiltersEnum;
use App\Enums\Product\ProductSortFieldsEnum;
use App\Exceptions\ProductNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductIndexRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function index(ProductIndexRequest $request): Response
    {
        return Inertia::render(
            component: 'Order/Pos',);
    }
}
