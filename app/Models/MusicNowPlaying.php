<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MusicNowPlaying extends Model
{
    protected $fillable = [
        'track_name',
        'artist_name',
        'album_name',
        'played_at',
        'artwork_url',
        'source',  // To track which service provided this data
        'metadata', // For any service-specific additional data
        'track_mbid',
        'artist_mbid',
        'album_mbid'
    ];

    protected $casts = [
        'played_at' => 'datetime',
        'metadata' => 'array'
    ];

    private static string $BASE_MB_URL = 'https://musicbrainz.org/';
    private static string $BASE_LB_URL = 'https://listenbrainz.org/';
    private static string $TRACK_URL = 'recording/';
    private static string $ARTIST_URL = 'artist/';
    private static string $ALBUM_URL = 'release/';

    public function getTrackUrlAttribute(): string
    {
        if ($this->track_mbid == "") {
            return "";
        }

        return self::$BASE_MB_URL . self::$TRACK_URL . $this->track_mbid;
    }

    public function getArtistUrlAttribute(): string
    {
        if ($this->artist_mbid == "") {
            return "";
        }

        return self::$BASE_LB_URL . self::$ARTIST_URL . $this->artist_mbid;
    }

    public function getAlbumUrlAttribute(): string
    {
        if ($this->album_mbid == "") {
            return "";
        }

        return self::$BASE_LB_URL . self::$ALBUM_URL . $this->album_mbid;
    }
}
