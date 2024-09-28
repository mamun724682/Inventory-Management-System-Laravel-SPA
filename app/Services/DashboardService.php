<?php

namespace App\Services;

use App\Enums\Expense\ExpenseFieldsEnum;
use App\Enums\Order\OrderFieldsEnum;
use App\Helpers\BaseHelper;
use App\Models\Expense;
use App\Models\Order;
use Carbon\Carbon;

class DashboardService
{
    public function getData(?string $date = null): array
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();

        // Get total order count, total profit, and total loss for the current month
        $selectedMonthOrders = Order::query()
            ->when($date, function ($query, $date) {
                $query->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year);
            })
            ->get();
        $selectedMonthTotalOrders = $selectedMonthOrders->count();
        $selectedMonthTotalProfit = $selectedMonthOrders->sum(OrderFieldsEnum::PROFIT->value);
        $selectedMonthTotalLoss = $selectedMonthOrders->sum(OrderFieldsEnum::LOSS->value);

        $lastMonthOrders = Order::query()
            ->when($date, function ($query, $date) {
                $query->whereMonth('created_at', $date->subMonth()->month)
                    ->whereYear('created_at', $date->subMonth()->year);
            })
            ->get();
        $lastMonthTotalOrders = $lastMonthOrders->count();
        $lastMonthTotalProfit = $lastMonthOrders->sum(OrderFieldsEnum::PROFIT->value);
        $lastMonthTotalLoss = $lastMonthOrders->sum(OrderFieldsEnum::LOSS->value);

        // Calculate percentage change for total orders, profit, and loss
        $orderPercentageChange = ($lastMonthTotalOrders != 0) ? (($selectedMonthTotalOrders - $lastMonthTotalOrders) / $lastMonthTotalOrders) * 100 : 0;
        $profitPercentageChange = ($lastMonthTotalProfit != 0) ? (($selectedMonthTotalProfit - $lastMonthTotalProfit) / $lastMonthTotalProfit) * 100 : 0;
        $lossPercentageChange = ($lastMonthTotalLoss != 0) ? (($selectedMonthTotalLoss - $lastMonthTotalLoss) / $lastMonthTotalLoss) * 100 : 0;

        $selectedMonthTotalExpenses = Expense::query()
            ->when($date, function ($query, $date) {
                $query->whereMonth(ExpenseFieldsEnum::EXPENSE_DATE->value, $date->month)
                    ->whereYear(ExpenseFieldsEnum::EXPENSE_DATE->value, $date->year);
            })
            ->sum(ExpenseFieldsEnum::AMOUNT->value);
        $lastMonthTotalExpenses = Expense::query()
            ->when($date, function ($query, $date) {
                $query->whereMonth(ExpenseFieldsEnum::EXPENSE_DATE->value, $date->subMonth()->month)
                    ->whereYear(ExpenseFieldsEnum::EXPENSE_DATE->value, $date->subMonth()->year);
            })
            ->sum(ExpenseFieldsEnum::AMOUNT->value);
        $expensePercentageChange = ($lastMonthTotalExpenses != 0) ? (($selectedMonthTotalExpenses - $lastMonthTotalExpenses) / $lastMonthTotalExpenses) * 100 : 0;

        return [
            "total_orders"      => [
                "selected"          => $selectedMonthTotalOrders,
                "percentage_change" => abs(BaseHelper::numberFormat($orderPercentageChange)),
                "stateArray"        => $orderPercentageChange < 0 ? "down" : "up"
            ],
            "total_profit"      => [
                "selected"          => (double) $selectedMonthTotalProfit,
                "percentage_change" => abs(BaseHelper::numberFormat($profitPercentageChange)),
                "stateArray"        => $profitPercentageChange < 0 ? "down" : "up"
            ],
            "total_loss"        => [
                "selected"          => (double) $selectedMonthTotalLoss,
                "percentage_change" => abs(BaseHelper::numberFormat($lossPercentageChange)),
                "stateArray"        => $lossPercentageChange < 0 ? "down" : "up"
            ],
            "total_expense"     => [
                "selected"          => (double) $selectedMonthTotalExpenses,
                "percentage_change" => abs(BaseHelper::numberFormat($expensePercentageChange)),
                "stateArray"        => $expensePercentageChange < 0 ? "down" : "up"
            ],
            "profit_line_chart" => $this->prepareProfitLineChart(),
            "orders_bar_chart"  => $this->prepareOrderBarChart(),
        ];
    }

    private function prepareProfitLineChart(): array
    {
        $currentYearProfit = Order::selectRaw('MONTH(created_at) as month, SUM(profit) as total_profit')
            ->whereYear('created_at', Carbon::now()->year)
            ->where('created_at', '>=', Carbon::now()->subMonths(7))
            ->groupBy('month')
            ->pluck('total_profit', 'month');

        $lastYearProfit = Order::selectRaw('MONTH(created_at) as month, SUM(profit) as total_profit')
            ->whereYear('created_at', Carbon::now()->subYear()->year)
            ->where('created_at', '>=', Carbon::now()->subYear()->subMonths(7))
            ->groupBy('month')
            ->pluck('total_profit', 'month');

        // Loop to get the last 7 months
        $months = [];
        $currentYearProfitValues = [];
        $lastYearProfitValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $carbon = Carbon::now()->subMonths($i);
            $months[] = $carbon->format('F');
            $currentYearProfitValues[] = (double) ($currentYearProfit[$carbon->month] ?? 0);
            $lastYearProfitValues[] = (double) ($lastYearProfit[$carbon->month] ?? 0);
        }

        return [
            "months"       => $months,
            "current_year" => $currentYearProfitValues,
            "last_year"    => $lastYearProfitValues,
        ];
    }

    private function prepareOrderBarChart(): array
    {
        $currentYearOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', Carbon::now()->year)
            ->where('created_at', '>=', Carbon::now()->subMonths(7))
            ->groupBy('month')
            ->pluck('total_orders', 'month');

        $lastYearOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', Carbon::now()->subYear()->year)
            ->where('created_at', '>=', Carbon::now()->subYear()->subMonths(7))
            ->groupBy('month')
            ->pluck('total_orders', 'month');

        // Loop to get the last 7 months
        $months = [];
        $currentYearOrdersValues = [];
        $lastYearOrdersValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $carbon = Carbon::now()->subMonths($i);
            $months[] = $carbon->format('F');
            $currentYearOrdersValues[] = (double) ($currentYearOrders[$carbon->month] ?? 0);
            $lastYearOrdersValues[] = (double) ($lastYearOrders[$carbon->month] ?? 0);
        }

        return [
            "months"       => $months,
            "current_year" => $currentYearOrdersValues,
            "last_year"    => $lastYearOrdersValues,
        ];
    }
}
