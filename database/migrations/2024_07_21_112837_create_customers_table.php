<?php

use App\Enums\Customer\CustomerFieldsEnum;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string(CustomerFieldsEnum::NAME->value);
            $table->string(CustomerFieldsEnum::EMAIL->value);
            $table->string(CustomerFieldsEnum::PHONE->value);
            $table->string(CustomerFieldsEnum::ADDRESS->value)->nullable();
            $table->string(CustomerFieldsEnum::PHOTO->value)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
