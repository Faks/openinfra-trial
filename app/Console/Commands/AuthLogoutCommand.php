<?php

declare(strict_types=1);

namespace App\Console\Commands;

use function ucwords;

class AuthLogoutCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @throws \JsonException|\Exception
     */
    public function handle(): void
    {
        $user = $this->user;

        if ($user) {
            $this->info('Goodbye, ' . ucwords($user->name));
            $this->unsetUser();
        } else {
            $this->info('Please Login and try again.');
        }
    }
}
