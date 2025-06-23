<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cnpj');
            $table->string('razao_social');
            $table->string('logradouro');
            $table->string('numero');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('funds');
    }
}; 