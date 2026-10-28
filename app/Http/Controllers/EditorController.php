<?php

namespace App\Http\Controllers;

use App\Models\MusicNowPlaying;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    /**
     * Show the text editor view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('editor.show');
    }

    /**
     * Handle file save request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $content = $request->input('content');
        $filename = $request->input('filename');

        // Set the response headers for file download
        $headers = [
            'Content-Type' => 'text/markdown',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Return the content as a downloadable file
        return response($content, 200, $headers);
    }
}
