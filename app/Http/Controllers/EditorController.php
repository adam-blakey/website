<?php

namespace App\Http\Controllers;

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
}
