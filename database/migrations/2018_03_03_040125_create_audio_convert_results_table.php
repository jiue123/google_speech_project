<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioConvertResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_convert_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('audio_file_id')->unsigned();
            $table->foreign('audio_file_id')->references('id')->on('audio_files')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->text('result_convert');
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
        Schema::dropIfExists('audio_convert_results');
    }
}
