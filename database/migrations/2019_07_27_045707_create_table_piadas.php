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
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->integer("curtidas")->default(0);
            $table->integer("deslikes")->default(0);
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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