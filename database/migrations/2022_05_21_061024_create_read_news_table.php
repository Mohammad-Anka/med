<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('read_news', function (Blueprint $table) {
            $table->id();
            /*  'user_id',
        'new_id',
        'read',*/
        $table->bigInteger('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users') ->onDelete('cascade')->onUpdate('cascade');
        $table->bigInteger('new_id')->unsigned();
        $table->foreign('new_id')->references('id')->on('newts') ->onDelete('cascade')->onUpdate('cascade');
             $table->boolean('read')->default(false);
             $table->unique(['user_id','new_id']);
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
        Schema::dropIfExists('read_news');
    }
}
