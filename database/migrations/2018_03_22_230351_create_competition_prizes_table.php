<?php

use Culpa\Database\Schema\Blueprint;
use Culpa\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCompetitionPrizesTable
 */
class CreateCompetitionPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_id')->unsigned()->index();
            $table->string('amount');
            $table->string('additional');
            $table->string('rank');
            $table->timestamps();

            $table->createdBy();
            $table->updatedBy();
            $table->deletedBy(true);

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_prizes');
    }
}
