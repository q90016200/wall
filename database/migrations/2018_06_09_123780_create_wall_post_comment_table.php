<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallPostCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wall_post_comments', function (Blueprint $table) {
            $table->bigIncrements('comment_id');
            $table->unsignedBigInteger('comment_post_id')->index();
            $table->unsignedBigInteger('comment_author')->index();
            $table->string('comment_content', 500);
            $table->timestamps();
            $table->integer('comment_like_count')->default(0);
            //相當於為軟刪除添加一個可空的 deleted_at 字段
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('wall_post_comments');
    }
}
