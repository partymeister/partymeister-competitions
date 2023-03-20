<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateOptionGroupsTable
 */
class CreateOptionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', ['multiple', 'single']);

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_group_id')->unsigned()->index();
            $table->integer('sort_position')->unsigned()->index();
            $table->string('name');

            $table->createdBy();
            $table->updatedBy();
            $table->deletedBy(true);

            $table->timestamps();

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
        Schema::dropIfExists('options');
        Schema::dropIfExists('option_groups');
    }
}
