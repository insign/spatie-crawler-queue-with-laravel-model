<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrawlerQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_queues', function (Blueprint $table) {
            $table->id();

            $table->string('url_hash', 128);
            $table->text('url');

            $table->longText('url_class');
            $table->longText('html')->nullable()->collation('utf8mb4_bin');

            $table->expires();
            $table->index('url_hash');
            $table->index('expires_at');

            $table->timestamps();
            // @OBS deleted_at = soft delete = processed
            $table->softDeletes()->comment('Means processed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawler_queues');
    }
}
