<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryPartCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_part_count', function (Blueprint $table) {
            $table->unsignedBigInteger('part_category_id');
            $table->unsignedInteger('part_count');

            $table->foreign('part_category_id')
                ->references('id')
                ->on('part_categories')
                ->onDelete('cascade')
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
        Schema::dropIfExists('category_part_count');
    }
}
