<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->string('part_num', 20)->unique();
            $table->string('name', 250);
            $table->unsignedBigInteger('part_category_id');
            $table->unsignedBigInteger('part_material_id')->default(1);

            $table->primary('part_num');

            $table->foreign('part_category_id')
                ->references('id')
                ->on('part_categories')
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
        Schema::dropIfExists('parts');
    }
}
