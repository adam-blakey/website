<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            $table->boolean('keep_local_edits')->default(false)->after('external_id');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('wordpress_id')->nullable()->change();
            $table->boolean('keep_local_edits')->default(false)->after('wordpress_id');
        });
    }

    public function down(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            $table->dropColumn('keep_local_edits');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('keep_local_edits');
            $table->unsignedBigInteger('wordpress_id')->nullable(false)->change();
        });
    }
};
