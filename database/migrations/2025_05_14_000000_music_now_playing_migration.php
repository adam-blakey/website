<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music_now_playings', function (Blueprint $table) {
            $table->id();
            $table->string('track_name');
            $table->string('artist_name');
            $table->string('album_name')->nullable();
            $table->string('track_url')->nullable();
            $table->string('source');  // lastfm, librefm, listenbrainz
            $table->timestamp('played_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_now_playings');
    }
};
