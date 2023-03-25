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
            $table->id();
            $table->bigInteger('entry_comments_page_id')->unsigned()->nullable()->index();
            $table->bigInteger('entry_screenshots_page_id')->unsigned()->nullable()->index();
            $table->bigInteger('entry_edit_page_id')->unsigned()->nullable()->index();
            $table->bigInteger('entry_detail_page_id')->unsigned()->nullable()->index();
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
