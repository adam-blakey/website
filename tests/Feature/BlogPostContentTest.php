<?php

use App\Models\BlogPost;

function safeContent(string $content): string
{
    return (new BlogPost(['content' => $content]))->safe_content;
}

it('keeps videos with their sources and fallback link', function () {
    $html = safeContent('<p><video width="100%" controls preload="metadata" muted src="/storage/blog-media/a.mp4"><source type="video/mp4" src="/storage/blog-media/a.mp4" />Try <a href="/storage/blog-media/a.mp4">the video</a>.</video></p>');

    expect($html)
        ->toContain('<video width="100%" controls="" preload="metadata" muted="" src="/storage/blog-media/a.mp4">')
        ->toContain('<source type="video/mp4" src="/storage/blog-media/a.mp4" />')
        ->toContain('Try <a href="/storage/blog-media/a.mp4"');
});

it('strips unsafe sources, handlers and autoplay from videos', function () {
    $html = safeContent('<video src="javascript:alert(1)" onplay="alert(1)" autoplay controls><source src="data:video/mp4;base64,AAAA"><track src="x.vtt"><script>alert(1)</script></video>');

    expect($html)->toBe('<video controls=""></video>');
});

it('still strips scripts and srcset from post content', function () {
    expect(safeContent('<p>Hi<script>alert(1)</script><img src="/a.jpg" srcset="/a.webp 320w" alt="" /></p>'))
        ->toBe('<p>Hi<img src="/a.jpg" alt="" /></p>');
});
