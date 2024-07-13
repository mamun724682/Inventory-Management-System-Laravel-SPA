<?php

use App\Enums\Supplier\SupplierFieldsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string(SupplierFieldsEnum::NAME->value);
            $table->string(SupplierFieldsEnum::EMAIL->value);
            $table->string(SupplierFieldsEnum::PHONE->value);
            $table->string(SupplierFieldsEnum::ADDRESS->value)->nullable();
            $table->string(SupplierFieldsEnum::PHOTO->value)->nullable();
            $table->string(SupplierFieldsEnum::SHOP_NAME->value)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
