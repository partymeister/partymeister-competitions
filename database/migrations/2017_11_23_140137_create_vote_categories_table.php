<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateVoteCategoriesTable
 */
class CreateVoteCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('points')->unsigned();
            $table->boolean('has_negative')->default(false);
            $table->boolean('has_comment')->default(false);
            $table->boolean('has_special_vote')->default(false);

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->timestamps();
        });

        Schema::create('competition_vote_category', function (Blueprint $table) {
            $table->bigInteger('competition_id')->unsigned()->index();
            $table->bigInteger('vote_category_id')->unsigned()->index();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('vote_category_id')->references('id')->on('vote_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_vote_category');
        Schema::dropIfExists('vote_categories');
    }
}
