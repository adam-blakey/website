<?php

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
