<?php

use App\Enums\Order\OrderFieldsEnum;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((new Order())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId(OrderFieldsEnum::CUSTOMER_ID->value)
                ->nullable()
                ->constrained((new Customer())->getTable())
                ->nullOnDelete();
            $table->string(OrderFieldsEnum::ORDER_NUMBER->value)->unique();
            $table->decimal(OrderFieldsEnum::SUB_TOTAL->value, 20, 8);
            $table->decimal(OrderFieldsEnum::TAX_TOTAL->value, 20, 8)->nullable();
            $table->decimal(OrderFieldsEnum::DISCOUNT_TOTAL->value, 20, 8)->nullable();
            $table->decimal(OrderFieldsEnum::TOTAL->value, 20, 8);
            $table->decimal(OrderFieldsEnum::PAID->value, 20, 8);
            $table->decimal(OrderFieldsEnum::DUE->value, 20, 8)->index();
            $table->decimal(OrderFieldsEnum::PROFIT->value, 20, 8)->index();
            $table->decimal(OrderFieldsEnum::LOSS->value, 20, 8)->index();
            $table->string(OrderFieldsEnum::STATUS->value, 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Order())->getTable());
    }
};
