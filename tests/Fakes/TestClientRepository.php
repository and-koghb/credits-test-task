<?php

namespace App\Tests\Fakes;

use App\Domain\Client\Client;
use App\Repositories\ClientRepository;

class TestClientRepository extends ClientRepository
{
    private array $clients = [];

    public function save(Client $client): void
    {
        $this->clients[] = $client;
    }

    public function findByPin(string $pin): ?Client
    {
        foreach ($this->clients as $client) {
            if ($client->getPin() === $pin) {
                return $client;
            }
        }
        return null;
    }

    public function all(): array
    {
        return $this->clients;
    }
}
