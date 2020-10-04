<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra', function (Blueprint $table) {
            $table->id();
            $table->integer('vat')->nullable(0);
            $table->string('logo');
            $table->string('favicon')->nullable(0);
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra');
    }
}
