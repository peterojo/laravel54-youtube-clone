<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id')->unsigned();
			$table->string('uuid');
			$table->string('title');
			$table->text('caption')->nullable();
			$table->boolean('processed')->default(false);
			$table->string('enc_video_id')->nullable();
			$table->string('enc_video_filename')->nullable();
			$table->enum('visibility', ['public', 'private', 'unlisted']);
			$table->boolean('allow_votes')->default(false);
			$table->boolean('allow_comments')->default(true);
			$table->integer('processed_percentage')->nullable();
			$table->softDeletes();
            $table->timestamps();

			$table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
