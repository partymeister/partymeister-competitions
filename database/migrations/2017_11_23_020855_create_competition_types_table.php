<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCompetitionTypesTable
 */
class CreateCompetitionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->boolean('has_platform')->default(false);
            $table->boolean('has_filesize')->default(false);
            $table->boolean('has_screenshot')->default(false);
            $table->boolean('has_audio')->default(false);
            $table->boolean('has_video')->default(false);
            $table->boolean('has_recordings')->default(false);
            $table->boolean('has_composer')->default(false);
            $table->boolean('has_running_time')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->integer('number_of_work_stages')->unsigned()->default(false);
            $table->boolean('has_remote_entries')->default(false);
            $table->boolean('file_is_optional')->default(false);

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

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
        Schema::dropIfExists('competition_types');
    }
}
