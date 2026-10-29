<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EditorController;

Route::get('/welcome', function () {
    return redirect('/');
})->name('welcome');

Route::get('/', function () {
    $latestTrack = \App\Models\MusicNowPlaying::latest('played_at')->first();
    $randomPost = \App\Models\BlogPost::with('featuredMedia')->inRandomOrder()->first();
    $nextConcert = \App\Models\Concert::getNextConcert();

    return view('welcome', [
        'latestTrack' => $latestTrack,
        'randomPost' => $randomPost,
        'nextConcert' => $nextConcert
    ]);
})->name('index');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/interests', function() {
    return view('interests');
})->name('interests');

Route::get('/interests/music', function() {
    $concerts = \App\Models\Concert::upcoming()->get();
    return view('interests.music', ['concerts' => $concerts]);
})->name('interests.music');

Route::get('/interests/scouts', function() {
    return view('interests.scouts');
})->name('interests.scouts');

Route::get('/interests/software', function() {
    return view('interests.software');
})->name('interests.software');

Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Editor routes
Route::get('/editor', [EditorController::class, 'show'])->name('editor.show');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Fun.
Route::get('/brew-coffee', function() {
    $random = rand(0, 9);

    // Teapots are much more common than combined tea/coffee pots.
    if ($random == 0) {
        return response("200: I'm a combined tea/coffee pot and we have plenty coffee", 200);
    }
    elseif ($random == 1) {
        return response("503: Temporarily out of coffee", 503);
    }
    else {
        return response("418: I'm a teapot", 418);
    }
});
