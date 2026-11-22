<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin {email} {name=Admin}';
    protected $description = 'Create a dashboard login, or reset the password of an existing one';

    public function handle(): int
    {
        $password = $this->secret('Password (at least 12 characters)');

        $validator = Validator::make(
            ['email' => $this->argument('email'), 'password' => $password],
            ['email' => 'required|email', 'password' => 'required|string|min:12'],
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $this->argument('email')],
            ['name' => $this->argument('name'), 'password' => $password],
        );

        $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated') . " dashboard login for {$user->email}.");

        return self::SUCCESS;
    }
}
