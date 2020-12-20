<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->string('story_id', 7);
            $table->string('username', 7);
            $table->tinyInteger('rate');

            $table->primary(['story_id', 'username']);
            $table->foreign('story_id')
                  ->constrained('stories', 'story_id')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('username')
                  ->constrained('users', 'username')
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
        Schema::dropIfExists('ratings');
    }
}
