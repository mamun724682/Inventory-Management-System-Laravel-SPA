<?php

use App\Enums\Salary\SalaryFieldsEnum;
use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId(SalaryFieldsEnum::EMPLOYEE_ID->value)
                ->constrained((new Employee())->getTable())
                ->cascadeOnDelete();
            $table->decimal(SalaryFieldsEnum::AMOUNT->value, 20, 8);
            $table->date(SalaryFieldsEnum::SALARY_DATE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
