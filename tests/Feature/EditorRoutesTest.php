<?php

it('returns a successful response for editor page', function () {
    $response = $this->get('/editor');
    $response->assertStatus(200);
});

it('can save editor content', function () {
    $response = $this->post('/editor/save', [
        'content' => 'Test content',
        'filename' => 'test.md'
    ]);

    // Assuming a successful save redirects back or returns a success status
    // Adjust this assertion based on the actual behavior of the save endpoint
    $response->assertStatus(200)
        ->assertSessionHasNoErrors();
});
