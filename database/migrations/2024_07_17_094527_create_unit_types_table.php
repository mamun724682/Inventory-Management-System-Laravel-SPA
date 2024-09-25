<?php

use App\Enums\UnitType\UnitTypeFieldsEnum;
use App\Models\UnitType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((new UnitType())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string(UnitTypeFieldsEnum::NAME->value);
            $table->string(UnitTypeFieldsEnum::SYMBOL->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new UnitType())->getTable());
    }
};
