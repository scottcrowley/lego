<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('set_num', 20)->index();
            $table->string('name', 256);
            $table->year('year');
            $table->string('set_img_url')->nullable();
            $table->string('set_url')->nullable();
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->integer('num_parts');
            $table->timestamps();

            $table->foreign('theme_id')
                ->references('id')
                ->on('themes')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sets');
    }
}
