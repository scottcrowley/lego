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
            $table->string('set_num', 20)->unique()->index();
            $table->string('name', 256);
            $table->year('year');
            $table->unsignedBigInteger('theme_id')->nullable()->index();
            $table->integer('num_parts');

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
