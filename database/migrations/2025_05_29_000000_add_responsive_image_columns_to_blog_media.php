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
        Schema::table('blog_media', function (Blueprint $table) {
            $table->string('path_small_webp')->nullable()->after('path');
            $table->string('path_medium_webp')->nullable()->after('path_small_webp');
            $table->string('path_large_webp')->nullable()->after('path_medium_webp');
            $table->string('path_xlarge_webp')->nullable()->after('path_large_webp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_media', function (Blueprint $table) {
            $table->dropColumn('path_small_webp');
            $table->dropColumn('path_medium_webp');
            $table->dropColumn('path_large_webp');
            $table->dropColumn('path_xlarge_webp');
        });
    }
};
