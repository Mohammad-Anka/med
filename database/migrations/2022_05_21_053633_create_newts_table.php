<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newts', function (Blueprint $table) {
            $table->id();
        
        $table->string('title');
        $table->text('subject');
        $table->boolean('isImage')->default(false);
        $table->string('image');
        $table->boolean('hide')->default(false);
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
        Schema::dropIfExists('newts');
    }
}
