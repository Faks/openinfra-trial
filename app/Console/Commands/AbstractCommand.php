<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Storage;

use function cache;
use function collect;
use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

abstract class AbstractCommand extends Command
{
    /**
     * Create a new command instance.
     *
     * @return void
     * @throws \Exception
     */
    public function __construct()
    {
        $this->apiURL = "https://openinfra-trial.solum-designum.eu/api";
        $this->user = $this->getUser();
        parent::__construct();
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    public function after(string $subject): string
    {
        return Str::after($subject, '=');
    }

    final public function setUser(string $key, object $value): void
    {
        cache()->put($key, $value);
    }

    /**
     * @return \Illuminate\Contracts\Cache\Repository|mixed
     * @throws \Exception
     */
    final public function getUser(): mixed
    {
        return cache()->get('user');
    }

    final public function unsetUser(): void
    {
        cache()->pull('user');
    }

    /**
     * @param object $accountHistory
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \JsonException
     */
    final public function storeHistory(object $accountHistory): void
    {
        $accountHistory = collect($accountHistory)->toArray();
        $user = $this->user;

        $storeAccountHistory = [];
        if (! Storage::exists(
            'json/account/history/' . $user->name . '.json'
        )) {
            $storeAccountHistory[] = $accountHistory;
            Storage::append(
                'json/account/history/' . $user->name . '.json',
                json_encode($storeAccountHistory, JSON_THROW_ON_ERROR),
            );
        } else {
            $storageAccountHistory = Storage::get(
                'json/account/history/' . $user->name . '.json'
            );
            $storeAccountHistory = json_decode(
                $storageAccountHistory,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $storeAccountHistory[] = $accountHistory;
            $jsonData = json_encode($storeAccountHistory, JSON_THROW_ON_ERROR);

            Storage::put(
                'json/account/history/' . $user->name . '.json',
                $jsonData
            );
        }
    }
}
