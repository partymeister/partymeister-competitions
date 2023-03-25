<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddCommentsTable
 */
class AddCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('visitor_id')->unsigned()->nullable()->index();
            $table->boolean('read_by_visitor');
            $table->boolean('read_by_organizer');
            $table->string('model_type');
            $table->integer('model_id')->unsigned()->index();
            $table->text('message');
            $table->string('author');
            $table->timestamps();

            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
