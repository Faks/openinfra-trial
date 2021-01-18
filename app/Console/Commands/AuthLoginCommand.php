<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Storage;

use function json_decode;
use function ucwords;

use const JSON_THROW_ON_ERROR;

class AuthLoginCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login {user} {pin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @throws \JsonException
     */
    public function handle(): void
    {
        $userName = $this->after($this->argument('user'));
        $userPin = $this->after($this->argument('pin'));

        $profilePath = 'json/profile/' . $userName;

        if (Storage::exists($profilePath . '.json')) {
            $storageResponse = Storage::get($profilePath . '.json');
            $responseUser = json_decode(
                $storageResponse,
                false,
                512,
                JSON_THROW_ON_ERROR
            );

            if ($this->user) {
                $this->unsetUser();
            }

            if ($responseUser->pin === $userPin) {
                $this->setUser(
                    'user',
                    $responseUser
                );

                $this->info('Welcome, ' . ucwords($responseUser->name));
            } else {
                $this->info('Invalid credentials');
            }
        } else {
            $this->info('Unable find user, please register.');
        }
    }
}
