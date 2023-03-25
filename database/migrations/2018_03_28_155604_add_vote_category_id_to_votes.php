<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddVoteCategoryIdToVotes
 */
class AddVoteCategoryIdToVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->bigInteger('vote_category_id')->after('competition_id')->unsigned()->index();
            $table->foreign('vote_category_id')->references('id')->on('vote_categories')->onDelete('cascade');

            $table->boolean('special_vote')->after('entry_id');
            $table->string('comment')->after('points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['vote_category_id']);
            $table->dropColumn('vote_category_id');
            $table->dropColumn('special_vote');
            $table->dropColumn('comment');
        });
    }
}
