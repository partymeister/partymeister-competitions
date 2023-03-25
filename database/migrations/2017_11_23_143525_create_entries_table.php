<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateEntriesTable
 */
class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id')->nullable()->unsigned()->index();
            $table->string('title');
            $table->string('author');
            $table->string('filesize');
            $table->string('platform');
            $table->integer('sort_position')->unsigned();
            $table->text('description');
            $table->text('organizer_description');
            $table->string('running_time');
            $table->string('custom_option');
            $table->string('ip_address');
            $table->boolean('allow_release')->default(true);
            $table->boolean('is_remote')->default(false);
            $table->boolean('is_recorded')->default(false);
            $table->boolean('upload_enabled')->default(false);
            $table->boolean('is_prepared')->default(false);
            $table->integer('status')->default(0);
            $table->string('author_name');
            $table->string('author_email');
            $table->string('author_phone');
            $table->string('author_address');
            $table->string('author_zip');
            $table->string('author_city');
            $table->string('author_country_iso_3166_1');
            $table->string('composer_name');
            $table->string('composer_email');
            $table->string('composer_phone');
            $table->string('composer_address');
            $table->string('composer_zip');
            $table->string('composer_city');
            $table->string('composer_country_iso_3166_1');
            $table->boolean('composer_not_member_of_copyright_collective')->default(false);

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->timestamps();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
