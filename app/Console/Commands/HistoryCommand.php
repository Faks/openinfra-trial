<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Storage;

use function json_decode;
use function print_r;

use const JSON_THROW_ON_ERROR;

class HistoryCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history';

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
            if (Storage::exists('json/account/history/' . $user->name . '.json')) {
                $fileStorage = Storage::get(
                    'json/account/history/' . $user->name . '.json'
                );
                $storeAccount = json_decode(
                    $fileStorage,
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );

                $this->info('History : ' . print_r($storeAccount));
            } else {
                $this->info('History not found');
            }
        } else {
            $this->info('Please Login and try again.');
        }
    }
}
