<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create categories table
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Create tags table
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Create media table
        Schema::create('blog_media', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_url')->nullable();
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });

        // Create posts table
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wordpress_id')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->unsignedBigInteger('featured_media_id')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();

            $table->foreign('featured_media_id')
                  ->references('id')
                  ->on('blog_media')
                  ->onDelete('set null');
        });

        // Create pivot tables for many-to-many relationships
        Schema::create('blog_post_category', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('category_id');
            $table->primary(['post_id', 'category_id']);

            $table->foreign('post_id')
                  ->references('id')
                  ->on('blog_posts')
                  ->onDelete('cascade');
            $table->foreign('category_id')
                  ->references('id')
                  ->on('blog_categories')
                  ->onDelete('cascade');
        });

        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['post_id', 'tag_id']);

            $table->foreign('post_id')
                  ->references('id')
                  ->on('blog_posts')
                  ->onDelete('cascade');
            $table->foreign('tag_id')
                  ->references('id')
                  ->on('blog_tags')
                  ->onDelete('cascade');
        });

        // Create table for post content images
        Schema::create('blog_post_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('media_id');
            $table->string('original_url');
            $table->timestamps();

            $table->foreign('post_id')
                  ->references('id')
                  ->on('blog_posts')
                  ->onDelete('cascade');
            $table->foreign('media_id')
                  ->references('id')
                  ->on('blog_media')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_images');
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_post_category');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_media');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
    }
};
