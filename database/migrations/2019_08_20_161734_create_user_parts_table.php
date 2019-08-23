<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_parts', function (Blueprint $table) {
            $table->string('part_num', 20)->index();
            $table->bigInteger('color_id')->index();
            $table->unsignedInteger('quantity');

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_parts');
    }
}
