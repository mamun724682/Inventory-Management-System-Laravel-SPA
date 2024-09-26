<?php

use App\Enums\OrderItem\OrderItemFieldsEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((new OrderItem())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId(OrderItemFieldsEnum::ORDER_ID->value)
                ->constrained((new Order())->getTable())
                ->cascadeOnDelete();
            $table->foreignId(OrderItemFieldsEnum::PRODUCT_ID->value)
                ->nullable()
                ->constrained((new Product())->getTable())
                ->nullOnDelete();
            $table->longText(OrderItemFieldsEnum::PRODUCT_JSON->value);
            $table->decimal(OrderItemFieldsEnum::QUANTITY->value, 20, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new OrderItem())->getTable());
    }
};
