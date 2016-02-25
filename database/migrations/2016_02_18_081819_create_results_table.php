<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('results', function (Blueprint $table) {
          $table->increments('id');
          $table->char('url',255)->unique();
          $table->char('portal',50);
          $table->char('page_title',255);
          $table->text('description')->nullable();
          $table->longtext('content');
          $table->date('date')->nullable();
          $table->integer('fb_likes')->default(0);
          $table->integer('fb_shares')->default(0);
          $table->integer('fb_comments')->default(0);
          $table->integer('gp_shares')->default(0);
          $table->integer('total_shares')->default(0);
          $table->boolean('exclude')->default(false);
          $table->boolean('isArticle')->default(false);

          $table->timestamp('last_update');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('results');
    }
}
