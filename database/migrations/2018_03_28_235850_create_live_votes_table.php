<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateLiveVotesTable
 */
class CreateLiveVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_votes', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id')->unsigned()->index();
            $table->integer('entry_id')->unsigned()->index();
            $table->integer('sort_position')->unsigned();
            $table->string('title');
            $table->string('author');
            $table->boolean('is_current');
            $table->timestamps();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_votes');
    }
}
