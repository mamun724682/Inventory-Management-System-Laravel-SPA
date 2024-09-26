<?php

use App\Enums\Product\ProductFieldsEnum;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\UnitType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId(ProductFieldsEnum::CATEGORY_ID->value)
                ->constrained((new Category())->getTable())
                ->cascadeOnDelete();
            $table->foreignId(ProductFieldsEnum::SUPPLIER_ID->value)
                ->nullable()
                ->constrained((new Supplier())->getTable())
                ->nullOnDelete();

            $table->string(ProductFieldsEnum::NAME->value);
            $table->string(ProductFieldsEnum::PRODUCT_NUMBER->value);
            $table->text(ProductFieldsEnum::DESCRIPTION->value)->nullable();
            $table->string(ProductFieldsEnum::PRODUCT_CODE->value)->nullable();
            $table->string(ProductFieldsEnum::ROOT->value)->nullable();
            $table->decimal(ProductFieldsEnum::BUYING_PRICE->value, 20, 8);
            $table->decimal(ProductFieldsEnum::SELLING_PRICE->value, 20, 8);
            $table->timestamp(ProductFieldsEnum::BUYING_DATE->value)->nullable();

            $table->foreignId(ProductFieldsEnum::UNIT_TYPE_ID->value)
                ->nullable()
                ->constrained((new UnitType())->getTable())
                ->nullOnDelete();

            $table->decimal(ProductFieldsEnum::QUANTITY->value, 20, 8);
            $table->string(ProductFieldsEnum::PHOTO->value);
            $table->string(ProductFieldsEnum::STATUS->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
