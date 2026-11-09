<?php

use App\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

it('logs the errors and warnings a command prints', function () {
    $logged = [];
    Log::listen(function (MessageLogged $message) use (&$logged) {
        $logged[] = [$message->level, $message->message, $message->context];
    });
    $this->app[Kernel::class]->registerCommand(new class extends Command
    {
        protected $signature = 'test:noisy';

        public function handle(): void
        {
            $this->info('fine');
            $this->warn('careful');
            $this->error('broke');
        }
    });

    expect(Artisan::call('test:noisy'))->toBe(0);

    expect($logged)->toBe([
        ['warning', 'careful', ['command' => 'test:noisy']],
        ['error', 'broke', ['command' => 'test:noisy']],
    ]);
});
