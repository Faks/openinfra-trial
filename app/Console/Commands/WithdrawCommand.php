<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Account;
use JetBrains\PhpStorm\Pure;
use Storage;

use function json_decode;
use function json_encode;
use function now;

use const JSON_THROW_ON_ERROR;

class WithdrawCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdraw {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'withdraw money from my account';


    /**
     * Execute the console command.
     *
     * @throws \JsonException|\Exception
     */
    public function handle(): void
    {
        $user = $this->user;
        $amount = (float)$this->after($this->argument('amount'));

        if ($user) {
            if (! Storage::exists('json/account/' . $user->name . '.json')) {
                $this->info("User deposit account not open.");
            } else {
                $fileStorage = Storage::get('json/account/' . $user->name . '.json');
                $accountBalance = json_decode(
                    $fileStorage,
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );

                $balance = $accountBalance->amount - $amount;

                $balanceUpdate = json_encode(
                    $this->dataModel($balance),
                    JSON_THROW_ON_ERROR
                );

                Storage::put(
                    'json/account/' . $user->name . '.json',
                    $balanceUpdate
                );

                $this->storeHistory(
                    (object)[
                        'action' => 'withdraw',
                        'withdraw' => $amount,
                        'balance' => $balance,
                        'created_at' => now()
                    ]
                );

                $this->info('Balance left: ' . $balance);
            }
        } else {
            $this->info('Please Login and try again.');
        }
    }

    /**
     * @param float $amount
     *
     * @return Account
     */
    #[Pure] private function dataModel(float $amount): Account
    {
        $account = new Account();
        $account->amount = $amount;

        return $account;
    }
}
