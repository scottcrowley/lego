<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_parts', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_id');
            $table->string('part_num');
            $table->bigInteger('color_id');
            $table->integer('quantity');
            $table->string('is_spare', 1);

            $table->foreign('part_num')
                ->references('part_num')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('inventory_parts');
    }
}
