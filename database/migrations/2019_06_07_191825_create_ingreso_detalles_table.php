<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idingreso')->unsigned();
            $table->foreign('idingreso')->references('id')->on('ingresos')->ondetele('cascade');
            $table->integer('idarticulo')->unsigned();
            $table->foreign('idarticulo')->references('id')->on('articulos');
            $table->integer('cantidad');
            $table->decimal('precio', 11, 2);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingreso_detalles');
    }
}
