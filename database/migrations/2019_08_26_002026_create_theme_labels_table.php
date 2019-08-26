<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_labels', function (Blueprint $table) {
            $table->unsignedBigInteger('theme_id')->primary();
            $table->string('parents_label')->nullable();
            $table->string('theme_label');

            $table->foreign('theme_id')
                ->references('id')
                ->on('themes')
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
        Schema::dropIfExists('theme_labels');
    }
}
