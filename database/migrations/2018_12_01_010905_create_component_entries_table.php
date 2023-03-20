<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateComponentEntriesTable
 */
class CreateComponentEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_comments_page_id')->unsigned()->nullable()->index();
            $table->integer('entry_screenshots_page_id')->unsigned()->nullable()->index();
            $table->integer('entry_edit_page_id')->unsigned()->nullable()->index();
            $table->integer('entry_detail_page_id')->unsigned()->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('component_entries');
    }
}
