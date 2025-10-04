<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatidasTable extends Migration
{
    public function up()
    {
        Schema::create('batidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->cascadeOnDelete();
            $table->enum('tipo', ['entrada','saida']);
            $table->timestamp('registrado_em');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('batidas');
    }
}
