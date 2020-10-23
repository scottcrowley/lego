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
            $table->string('rel_type', 1)->nullable()->comment('(P)rint, Pai(R), Su(B)-Part, (M)old, Pa(T)tern, (A)lternate');
            $table->string('child_part_num', 20)->index();
            $table->string('parent_part_num', 20)->index();

            // $table->foreign('child_part_num')
            //     ->references('part_num')
            //     ->on('parts')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');

            // $table->foreign('parent_part_num')
            //     ->references('part_num')
            //     ->on('parts')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
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
