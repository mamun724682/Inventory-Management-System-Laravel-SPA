<template>
    <div class="flex flex-wrap">
        <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
            <card-stats
                statSubtitle="ORDERS"
                :statTitle="total_orders.selected"
                :statArrow="total_orders.stateArray"
                :statPercent="total_orders.percentage_change"
                :statPercentColor="total_orders.stateArray === 'up' ? 'text-emerald-500' : 'text-red-500'"
                statDescripiron="Since last month"
                statIconName="fas fa-chart-pie"
                statIconColor="bg-red-500"
            />
        </div>
        <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
            <card-stats
                statSubtitle="PROFIT"
                :statTitle="total_profit.selected"
                :statArrow="total_profit.stateArray"
                :statPercent="total_profit.percentage_change"
                :statPercentColor="total_profit.stateArray === 'up' ? 'text-emerald-500' : 'text-red-500'"
                statDescripiron="Since last month"
                statIconName="fas fa-chart-bar"
                statIconColor="bg-orange-500"
            />
        </div>
        <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
            <card-stats
                statSubtitle="LOSS"
                :statTitle="total_loss.selected"
                :statArrow="total_loss.stateArray"
                :statPercent="total_loss.percentage_change"
                :statPercentColor="total_loss.stateArray === 'up' ? 'text-emerald-500' : 'text-red-500'"
                statDescripiron="Since last month"
                statIconName="fas fa-thumbs-down"
                statIconColor="bg-pink-500"
            />
        </div>
        <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
            <card-stats
                statSubtitle="EXPENSES"
                :statTitle="total_expense.selected"
                :statArrow="total_expense.stateArray"
                :statPercent="total_expense.percentage_change"
                :statPercentColor="total_expense.stateArray === 'up' ? 'text-emerald-500' : 'text-red-500'"
                statDescripiron="Since last month"
                statIconName="fas fa-money-bill"
                statIconColor="bg-emerald-500"
            />
        </div>
    </div>
</template>

<script setup>
import CardStats from "@/Components/Cards/CardStats.vue";

defineProps({
    total_orders: Object,
    total_profit: Object,
    total_loss: Object,
    total_expense: Object
});
</script>
