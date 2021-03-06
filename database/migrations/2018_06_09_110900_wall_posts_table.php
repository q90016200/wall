<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WallPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wall_posts', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->unsignedBigInteger('post_author')->index();
            $table->text('post_content');
            
            $table->integer('post_comment_count')->default(0);
            $table->integer('post_image_count')->default(0);
            $table->integer('post_like_count')->default(0);
            $table->string('post_preview_link',2083)->nullable($value = true)->default(null);
            $table->ipAddress('post_ip')->nullable($value = true)->default(null);

            $table->timestamps();

            //相當於為軟刪除添加一個可空的 deleted_at 字段
            $table->softDeletes(); 
        });

        Schema::create('wall_preview_links', function (Blueprint $table) {
            $table->bigIncrements('link_id');
            
            $table->string('link_url',2083);
            $table->string('link_title',255)->nullable($value = true)->default(null);
            $table->string('link_description',255)->nullable($value = true)->default(null);
            $table->string('link_image',255)->nullable($value = true)->default(null);
            $table->integer('link_image_width')->nullable($value = true)->default(null);
            $table->integer('link_image_height')->nullable($value = true)->default(null);

            $table->timestamp('link_updated');
            
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
