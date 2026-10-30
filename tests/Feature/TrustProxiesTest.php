<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Monicahq\Cloudflare\LaravelCloudflare;

beforeEach(function () {
    config(['laravelcloudflare.enabled' => true]);
    LaravelCloudflare::getProxiesUsing(fn () => ['173.245.48.0/20']);
    Route::get('/client-ip', fn (Request $request) => $request->ip());
});

afterEach(fn () => LaravelCloudflare::getProxiesUsing(null));

it('takes the visitor ip from a cloudflare proxy', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '173.245.48.1'])
        ->withHeader('X-Forwarded-For', '203.0.113.9')
        ->get('/client-ip')
        ->assertSeeText('203.0.113.9');
});

it('ignores forwarded ips from anyone else', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.7'])
        ->withHeader('X-Forwarded-For', '203.0.113.9')
        ->get('/client-ip')
        ->assertSeeText('198.51.100.7');
});
