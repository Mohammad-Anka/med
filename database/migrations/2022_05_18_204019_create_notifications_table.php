<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle');
            $table->text('subject');
 
            $table->boolean('succSend')->default(false);
            $table->boolean('hide')->default(false);
            $table->boolean('read')->default(false);
            $table->boolean('isImage')->default(false);
            $table->string('type');
            $table->string('image')->nullable(); 
            $table->string('appointment')->nullable();  

           // $table->string('firebase_token')->nullable();  
            $table->bigInteger('user_id')->unsigned(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('notifications');
    }
}
