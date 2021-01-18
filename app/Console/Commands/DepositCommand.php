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

class DepositCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deposit in bank account';

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
                $account = $this->dataModel($amount);
                Storage::append(
                    'json/account/' . $user->name . '.json',
                    json_encode($account->toArray(), JSON_THROW_ON_ERROR),
                );

                $this->storeHistory(
                    (object)[
                        'action' => 'deposit',
                        'deposit' => $amount,
                        'balance' => $amount,
                        'created_at' => now()
                    ]
                );

                $this->info('Balance left: ' . $amount);
            } else {
                $fileStorage = Storage::get('json/account/' . $user->name . '.json');
                $storeAccount = json_decode(
                    $fileStorage,
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );

                $balance = $storeAccount->amount + $amount;

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
                        'action' => 'deposit',
                        'deposit' => $amount,
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
    #[Pure] private function dataModel(float $amount = 0.0): Account
    {
        $account = new Account();
        $account->amount = $amount;

        return $account;
    }
}
