<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Storage;

use function json_decode;
use function now;

use const JSON_THROW_ON_ERROR;

class BalanceCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bank account balance';

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \JsonException
     */
    public function handle(): void
    {
        $user = $this->user;

        if ($user) {
            if (Storage::exists('json/account/' . $user->name . '.json')) {
                $fileStorage = Storage::get('json/account/' . $user->name . '.json');
                $storeAccount = json_decode(
                    $fileStorage,
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );

                $balance = (float)$storeAccount->amount;

                $this->storeHistory(
                    (object)[
                        'action' => 'balance',
                        'balance' => $balance,
                        'created_at' => now()
                    ]
                );

                $this->info($balance);
            } else {
                $this->storeHistory(
                    (object)[
                        'action' => 'balance',
                        'balance' => 0,
                        'created_at' => now()
                    ]
                );

                $this->info(0);
            }
        } else {
            $this->info('Please Login and try again.');
        }
    }
}
