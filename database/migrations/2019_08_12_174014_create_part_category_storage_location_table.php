<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartCategoryStorageLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_category_storage_location', function (Blueprint $table) {
            $table->bigInteger('part_category_id');
            $table->bigInteger('storage_location_id');

            $table->primary(['part_category_id', 'storage_location_id']);

            $table->foreign('part_category_id')
                ->references('id')
                ->on('part_categories');

            $table->foreign('storage_location_id')
                ->references('id')
                ->on('storage_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_category_storage_location');
    }
}
