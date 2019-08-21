<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetImageUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_image_urls', function (Blueprint $table) {
            $table->string('set_num', 20);
            $table->string('image_url');

            $table->primary(['set_num', 'image_url']);

            $table->foreign('set_num')
                ->references('set_num')
                ->on('sets')
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
        Schema::dropIfExists('set_image_urls');
    }
}
