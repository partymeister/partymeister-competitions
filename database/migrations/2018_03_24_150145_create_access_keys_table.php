<?php

use Culpa\Database\Schema\Blueprint;
use Culpa\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAccessKeysTable
 */
class CreateAccessKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('visitor_id')->unsigned()->index()->nullable();
            $table->string('access_key');
            $table->string('ip_address');
            $table->datetime('registered_at')->nullable();

            $table->timestamps();

            $table->createdBy();
            $table->updatedBy();
            $table->deletedBy(true);

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
        Schema::dropIfExists('access_keys');
    }
}
