<?php

use App\Enums\Employee\EmployeeFieldsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string(EmployeeFieldsEnum::NAME->value)->index();
            $table->string(EmployeeFieldsEnum::EMAIL->value)->unique();
            $table->string(EmployeeFieldsEnum::PHONE->value);
            $table->string(EmployeeFieldsEnum::DESIGNATION->value);
            $table->string(EmployeeFieldsEnum::ADDRESS->value);
            $table->decimal(EmployeeFieldsEnum::SALARY->value, 20, 8);
            $table->string(EmployeeFieldsEnum::PHOTO->value)->nullable();
            $table->string(EmployeeFieldsEnum::NID->value)->nullable();
            $table->date(EmployeeFieldsEnum::JOINING_DATE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
