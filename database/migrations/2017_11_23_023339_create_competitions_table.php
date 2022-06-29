<?php

use Culpa\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCompetitionsTable
 */
class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_type_id')->nullable()->unsigned()->index();
            $table->integer('sort_position')->unsigned()->index();
            $table->integer('prizegiving_sort_position')->unsigned()->index();
            $table->string('name');
            $table->boolean('has_prizegiving')->default(true);
            $table->boolean('upload_enabled')->default(true);
            $table->boolean('voting_enabled')->default(false);

            $table->createdBy();
            $table->updatedBy();
            $table->deletedBy(true);

            $table->timestamps();

            $table->foreign('competition_type_id')->references('id')->on('competition_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitions');
    }
}
