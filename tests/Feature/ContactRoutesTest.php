<?php

it('returns a successful response for contact page', function () {
    $response = $this->get('/contact');
    $response->assertStatus(200);
});

it('validates recaptcha on contact form submission', function () {
    $response = $this->post('/contact', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'message' => 'This is a test message'
        // Intentionally omitting g-recaptcha-response
    ]);

    // Since we can't provide a valid reCAPTCHA token in tests,
    // we'll verify that the form correctly validates its presence
    $response->assertSessionHasErrors('g-recaptcha-response');
});

it('validates contact form input', function () {
    // Test with empty data to trigger validation
    $response = $this->post('/contact', [
        'name' => '',
        'email' => '',
        'message' => ''
    ]);

    // Expecting validation errors
    $response->assertSessionHasErrors(['name', 'email', 'message']);
});
