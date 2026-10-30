<?php

use Illuminate\Support\Facades\View;

it('returns a successful response for home page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('redirects from welcome to home page', function () {
    $response = $this->get('/welcome');
    $response->assertRedirect('/');
});

it('returns a successful response for about page', function () {
    $response = $this->get('/about');
    $response->assertStatus(200);
});

it('returns a successful response for awards page', function () {
    $response = $this->get('/about/awards');
    $response->assertStatus(200);
});

it('returns a successful response for talks page', function () {
    $response = $this->get('/about/talks');
    $response->assertStatus(200);
});

it('returns a successful response for interests page', function () {
    $response = $this->get('/interests');
    $response->assertStatus(200);
});

it('returns a successful response for music interests page', function () {
    $response = $this->get('/interests/music');
    $response->assertStatus(200);
});

it('returns a successful response for scouts interests page', function () {
    $response = $this->get('/interests/scouts');
    $response->assertStatus(200);
});

it('returns a successful response for software interests page', function () {
    $response = $this->get('/interests/software');
    $response->assertStatus(200);
});

it('returns a successful response for now page', function () {
    $response = $this->get('/now');
    $response->assertStatus(200);
});

it('links the version to its release', function () {
    View::share('layoutTaggedVersion', '1.4.0');
    $this->get('/')->assertSee('href="https://github.com/adam-blakey/website/releases/tag/v1.4.0"', false);
});

it('links a staging version to its pull request', function () {
    View::share('layoutTaggedVersion', '1.4.0-2-gabc1234+pr62');
    $this->get('/')->assertSee('href="https://github.com/adam-blakey/website/pull/62"', false);
});
