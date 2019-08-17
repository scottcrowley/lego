<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartStorageLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_storage_location', function (Blueprint $table) {
            $table->string('part_num', 20);
            $table->unsignedBigInteger('storage_location_id');

            $table->primary(['part_num', 'storage_location_id'], 'part_storage_id');

            $table->foreign('part_num', 'fk_part_num')
                ->references('part_num')
                ->on('parts');

            $table->foreign('storage_location_id', 'fk_storage_location_id')
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
        Schema::dropIfExists('part_storage_location');
    }
}
