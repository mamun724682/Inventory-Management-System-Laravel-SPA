<?php

use App\Enums\Transaction\TransactionFieldsEnum;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((new Transaction())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId(TransactionFieldsEnum::ORDER_ID->value)
                ->constrained((new Order())->getTable())
                ->cascadeOnDelete();
            $table->string(TransactionFieldsEnum::TRANSACTION_NUMBER->value);
            $table->string(TransactionFieldsEnum::AMOUNT->value);
            $table->string(TransactionFieldsEnum::PAID_THROUGH->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Transaction())->getTable());
    }
};
