<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->string('comment_id', 7);
            $table->string('chapter_id', 7);
            $table->string('username', 50);
            $table->text('content');

            $table->primary('comment_id');
            $table->foreign('chapter_id')
                  ->references('chapter_id')
                  ->on('chapters')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('username')
                  ->references('username')
                  ->on('users')
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
        Schema::dropIfExists('comments');
    }
}
