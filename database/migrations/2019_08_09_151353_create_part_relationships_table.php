<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_relationships', function (Blueprint $table) {
            $table->string('rel_type', 1)->nullable();
            $table->string('child_part_num', 20);
            $table->string('parent_part_num', 20);

            $table->foreign('child_part_num')
                ->references('part_num')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('parent_part_num')
                ->references('part_num')
                ->on('parts')
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
        Schema::dropIfExists('part_relationships');
    }
}
