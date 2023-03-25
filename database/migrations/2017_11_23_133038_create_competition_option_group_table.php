<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCompetitionOptionGroupTable
 */
class CreateCompetitionOptionGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_option_group', function (Blueprint $table) {
            $table->bigInteger('competition_id')->unsigned()->index();
            $table->bigInteger('option_group_id')->unsigned()->index();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('option_group_id')->references('id')->on('option_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_option_group');
    }
}
