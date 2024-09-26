<?php

namespace App\Http\Controllers;

use App\Enums\Core\FilterFieldTypeEnum;
use App\Enums\Core\FilterResourceEnum;
use App\Enums\Core\SortOrderEnum;
use App\Enums\Customer\CustomerFieldsEnum;
use App\Enums\Order\OrderExpandEnum;
use App\Enums\Order\OrderFiltersEnum;
use App\Enums\Order\OrderSortFieldsEnum;
use App\Enums\Order\OrderStatusEnum;
use App\Enums\Product\ProductExpandEnum;
use App\Enums\Transaction\TransactionPaidThroughEnum;
use App\Exceptions\OrderCreateException;
use App\Exceptions\OrderNotFoundException;
use App\Helpers\BaseHelper;
use App\Http\Requests\Order\OrderCreateRequest;
use App\Http\Requests\Order\OrderIndexRequest;
use App\Http\Requests\Order\OrderPaymentRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service)
    {
    }

    public function index(OrderIndexRequest $request)
    {
        $params = $request->validated();
        if ($request->inertia == "disabled"){
            return $this->service->getAll($params);
        }

        $params['expand'] = array_unique(array_merge($params['expand'] ?? [], [
            OrderExpandEnum::CUSTOMER->value,
            OrderExpandEnum::ORDER_ITEMS_PRODUCT->value . '.' . ProductExpandEnum::UNIT_TYPE->value,
        ]));

        return Inertia::render(
            component: 'Order/Index',
            props: [
                'orders'           => $this->service->getAll($params),
                'orderPaidByTypes' => BaseHelper::convertKeyValueToLabelValueArray(TransactionPaidThroughEnum::choices()),
                'filters'          => [
                    OrderFiltersEnum::ORDER_NUMBER->value => [
                        'label'       => OrderFiltersEnum::ORDER_NUMBER->label(),
                        'placeholder' => 'Enter order number.',
                        'type'        => FilterFieldTypeEnum::STRING->value,
                        'value'       => $request->validated()[OrderFiltersEnum::ORDER_NUMBER->value] ?? "",
                    ],
                    OrderFiltersEnum::CUSTOMER_ID->value  => [
                        'label'       => OrderFiltersEnum::CUSTOMER_ID->label(),
                        'placeholder' => 'Select customer.',
                        'type'        => FilterFieldTypeEnum::SELECT->value,
                        'value'       => $request->validated()[OrderFiltersEnum::CUSTOMER_ID->value] ?? "",
                        'resource'    => FilterResourceEnum::CUSTOMERS->value,
                        'resourceLabel' => CustomerFieldsEnum::NAME->value,
                    ],
                    OrderFiltersEnum::SUB_TOTAL->value    => [
                        'label'       => OrderFiltersEnum::SUB_TOTAL->label(),
                        'placeholder' => 'Enter sub total.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[OrderFiltersEnum::SUB_TOTAL->value] ?? "",
                    ],
                    OrderFiltersEnum::TOTAL->value        => [
                        'label'       => OrderFiltersEnum::TOTAL->label(),
                        'placeholder' => 'Enter sub total.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[OrderFiltersEnum::TOTAL->value] ?? "",
                    ],
                    OrderFiltersEnum::DUE->value          => [
                        'label'       => OrderFiltersEnum::DUE->label(),
                        'placeholder' => 'Enter due.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[OrderFiltersEnum::DUE->value] ?? "",
                    ],
                    OrderFiltersEnum::PROFIT->value       => [
                        'label'       => OrderFiltersEnum::PROFIT->label(),
                        'placeholder' => 'Enter profit.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[OrderFiltersEnum::PROFIT->value] ?? "",
                    ],
                    OrderFiltersEnum::LOSS->value         => [
                        'label'       => OrderFiltersEnum::LOSS->label(),
                        'placeholder' => 'Enter loss.',
                        'type'        => FilterFieldTypeEnum::NUMBER_RANGE->value,
                        'value'       => $request->validated()[OrderFiltersEnum::LOSS->value] ?? "",
                    ],
                    OrderFiltersEnum::STATUS->value       => [
                        'label'       => OrderFiltersEnum::STATUS->label(),
                        'placeholder' => 'Select status.',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()[OrderFiltersEnum::STATUS->value] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(OrderStatusEnum::choices()),
                    ],
                    "sort_by"                             => [
                        'label'       => 'Sort By',
                        'placeholder' => 'Select a sort field',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_by'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(OrderSortFieldsEnum::choices()),
                    ],
                    "sort_order"                          => [
                        'label'       => 'Sort order',
                        'placeholder' => 'Select a sort order',
                        'type'        => FilterFieldTypeEnum::SELECT_STATIC->value,
                        'value'       => $request->validated()['sort_order'] ?? "",
                        'options'     => BaseHelper::convertKeyValueToLabelValueArray(SortOrderEnum::choices()),
                    ],
                    OrderFiltersEnum::CREATED_AT->value   => [
                        'label'       => OrderFiltersEnum::CREATED_AT->label(),
                        'placeholder' => 'Enter created at.',
                        'type'        => FilterFieldTypeEnum::DATETIME_RANGE->value,
                        'value'       => $request->validated()[OrderFiltersEnum::CREATED_AT->value] ?? "",
                    ],
                ],
            ]);
    }

    public function store(OrderCreateRequest $request): RedirectResponse
    {
        try {
            $this->service->createForUser(
                payload: $request->validated(),
                userId: auth()->id()
            );
            $flash = [
                "message" => 'Order placed successfully.'
            ];
        } catch (OrderCreateException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Failed to place order.!",
            ];

            Log::error("Failed to place order", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('carts.index')
            ->with('flash', $flash);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function settle(int $id): RedirectResponse
    {
        try {
            $this->service->settle(id: $id);
            $flash = [
                "message" => 'Order settled successfully.'
            ];
        } catch (OrderNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Order settlement failed!",
            ];

            Log::error("Order settlement failed", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('orders.index')
            ->with('flash', $flash);
    }

    /**
     * @param OrderPaymentRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function pay(OrderPaymentRequest $request, $id): RedirectResponse
    {
        try {
            $this->service->pay(
                id: $id,
                payload: $request->validated()
            );
            $flash = [
                "message" => 'Payment added successfully.'
            ];
        } catch (OrderNotFoundException $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => $e->getMessage(),
            ];
        } catch (Exception $e) {
            $flash = [
                "isSuccess" => false,
                "message"   => "Order payment failed!",
            ];

            Log::error("Order payment failed", [
                "message" => $e->getMessage(),
                "traces"  => $e->getTrace()
            ]);
        }

        return redirect()
            ->route('orders.index')
            ->with('flash', $flash);
    }
}
