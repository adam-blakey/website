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
        Schema::table('music_now_playings', function (Blueprint $table) {
            $table->string('track_mbid')->nullable();
            $table->string('artist_mbid')->nullable();
            $table->string('album_mbid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('music_now_playings', function (Blueprint $table) {
            $table->dropColumn('track_mbid');
            $table->dropColumn('artist_mbid');
            $table->dropColumn('album_mbid');
        });
    }
};
