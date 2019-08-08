<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePiadas extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('piadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("descricao");
            $table->string("categoria")->default("geral");
            $table->integer("curtidas")->default(0);
            $table->integer("deslikes")->default(0);

        });
    }

    /**
     * Reverse the migrations. 
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piadas');
    }
}