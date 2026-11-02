<?php

it('opens top menu dropdowns on touch devices', function () {
    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    $css = file_get_contents(public_path('build/'.$manifest['resources/css/app.css']['file']));

    expect($css)->toContain('.group-hover\:block')
        ->not->toMatch('/@media\s*\(hover:\s*hover\)/');
});

it('lists every page in the open file dialog', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('href="#open-file"', false)
        ->assertSee('<dialog id="open-file"', false)
        ->assertSee('href="'.route('about.awards').'"', false)
        ->assertSee('href="'.route('blog.index').'"', false);
});
