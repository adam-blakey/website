<?php

it('returns a successful response for editor page', function () {
    $response = $this->get('/editor');
    $response->assertStatus(200);
});

it('has no server-side save endpoint', function () {
    $this->post('/editor/save', ['content' => 'x', 'filename' => 'a.md'])->assertStatus(404);
});
