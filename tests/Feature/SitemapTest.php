<?php

it('serves a sitemap of public pages', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee('<loc>' . url('/about') . '</loc>', false)
        ->assertDontSee(url('/welcome'), false)
        ->assertDontSee(url('/sitemap.xml'), false)
        ->assertDontSee(url('/robots.txt'), false)
        ->assertDontSee(url('/editor'), false);
});

it('points crawlers to the sitemap from robots.txt', function () {
    $this->get('/robots.txt')
        ->assertStatus(200)
        ->assertSee("User-agent: *\nAllow: /\n", false)
        ->assertSee('Sitemap: ' . url('/sitemap.xml'), false);
});
