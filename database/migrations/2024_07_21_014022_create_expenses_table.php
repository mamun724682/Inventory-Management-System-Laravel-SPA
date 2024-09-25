<?php

use App\Enums\Expense\ExpenseFieldsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string(ExpenseFieldsEnum::NAME->value);
            $table->text(ExpenseFieldsEnum::DESCRIPTION->value)->nullable();
            $table->decimal(ExpenseFieldsEnum::AMOUNT->value, 20, 8)->index();
            $table->date(ExpenseFieldsEnum::EXPENSE_DATE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
