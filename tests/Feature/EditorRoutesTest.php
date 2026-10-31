<?php

it('returns a successful response for editor page', function () {
    $response = $this->get('/editor');
    $response->assertStatus(200);
    $response->assertSee('href="#save-file"', false);
});

it('has no server-side save endpoint', function () {
    $this->post('/editor/save', ['content' => 'x', 'filename' => 'a.md'])->assertStatus(404);
});
