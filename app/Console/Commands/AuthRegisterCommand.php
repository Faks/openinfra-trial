<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Storage;
use Throwable;

class  AuthRegisterCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register {user} {pin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register bank account';

    /**
     * @throws Throwable
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \JsonException
     */
    public function handle(): void
    {
        $userName = $this->after($this->argument('user'));
        $userPin = $this->after($this->argument('pin'));

        $user = new User;
        $user->id = Uuid::uuid4();
        $user->name = $userName;
        $user->pin = $userPin;

        $profilePath = 'json/profile/' . $user->name;

        if (! $this->user) {
            if (! Storage::exists($profilePath . '.json')) {
                Storage::append(
                    $profilePath . '.json',
                    $user->toJson()
                );

                $this->info('User has been registered');
            } else {
                $this->info('User is present, unable create duplicate.');
            }
        } else {
            $this->info('Unable to register while, session in progress.');
        }
    }
}
