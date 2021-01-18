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

class TransferCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer {amount} {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer money to other user';

    /**
     * Execute the console command.
     *
     * @throws \JsonException|\Exception
     */
    public function handle(): void
    {
        $user = $this->user;
        $transferToUser = $this->after($this->argument('user'));
        $amount = (float)$this->after($this->argument('amount'));

        if ($user) {
            if (Storage::exists('json/account/' . $user->name . '.json')) {
                $fileStorage = Storage::get('json/account/' . $user->name . '.json');
                $storeAccount = json_decode(
                    $fileStorage,
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );

                if ($storeAccount->amount >= 1 || (int)$storeAccount->amount >= 1) {
                    $balance = $storeAccount->amount - $amount;

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
                            'action' => 'transfer',
                            'deposit' => $amount,
                            'balance' => $balance,
                            'to' => $transferToUser,
                            'created_at' => now()
                        ]
                    );

                    $this->info('Balance after transfer : ' . $balance);

                    // Transfer to other user
                    if (Storage::exists(
                        'json/account/' . $transferToUser . '.json'
                    )) {
                        $transferStorage = Storage::get(
                            'json/account/' . $transferToUser .
                            '.json'
                        );
                        $storeTransferAccount = json_decode(
                            $transferStorage,
                            false,
                            512,
                            JSON_THROW_ON_ERROR
                        );

                        $balance = $storeTransferAccount->amount + $amount;

                        $balanceUpdate = json_encode(
                            $this->dataModel($balance),
                            JSON_THROW_ON_ERROR
                        );

                        Storage::put(
                            'json/account/' . $transferToUser . '.json',
                            $balanceUpdate
                        );

                        $this->storeHistory(
                            (object)[
                                'action' => 'transfer',
                                'deposit' => $amount,
                                'balance' => $balance,
                                'from' => $user->name,
                                'created_at' => now()
                            ]
                        );
                    }
                } else {
                    $this->info('too low balance to transfer money');
                }
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
