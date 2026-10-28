<?php

namespace App\Http\Controllers;

use App\Models\MusicNowPlaying;

class WelcomeController extends Controller
{
    public function index()
    {
        $tracks = MusicNowPlaying::where('created_at', '>=', now()->subMinutes(5))
            ->orderBy('played_at', 'desc')
            ->get()
            ->unique('source');

        return view('welcome', [
            'tracks' => $tracks
        ]);
    }
}
