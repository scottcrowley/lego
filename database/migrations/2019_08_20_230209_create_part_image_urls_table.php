<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartImageUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_image_urls', function (Blueprint $table) {
            $table->string('part_num', 20)->index();
            $table->string('image_url');

            $table->unique(['part_num', 'image_url']);

            $table->foreign('part_num')
                ->references('part_num')
                ->on('parts')
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
        Schema::dropIfExists('part_image_urls');
    }
}
