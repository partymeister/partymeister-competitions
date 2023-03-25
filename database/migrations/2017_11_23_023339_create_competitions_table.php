<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->id();
            $table->integer('competition_type_id')->nullable()->unsigned()->index();
            $table->integer('sort_position')->unsigned()->index();
            $table->integer('prizegiving_sort_position')->unsigned()->index();
            $table->string('name');
            $table->boolean('has_prizegiving')->default(true);
            $table->boolean('upload_enabled')->default(true);
            $table->boolean('voting_enabled')->default(false);

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

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
