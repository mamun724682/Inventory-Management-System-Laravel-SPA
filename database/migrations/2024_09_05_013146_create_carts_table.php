<?php

use App\Enums\Cart\CartFieldsEnum;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((new Cart())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId(CartFieldsEnum::USER_ID->value)
                ->constrained((new User())->getTable())
                ->cascadeOnDelete();
            $table->foreignId(CartFieldsEnum::PRODUCT_ID->value)
                ->constrained((new Product())->getTable())
                ->cascadeOnDelete();
            $table->decimal(CartFieldsEnum::QUANTITY->value, 20, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Cart())->getTable());
    }
};
