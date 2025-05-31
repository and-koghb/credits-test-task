<?php

namespace App\Repositories;

use App\Domain\Client\Client;
use Symfony\Component\Serializer\SerializerInterface;

class ClientRepository
{
    private string $filePath;
    private SerializerInterface $serializer;

    public function __construct(string $filePath, SerializerInterface $serializer)
    {
        $this->filePath = $filePath;
        $this->serializer = $serializer;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, '[]');
        }
    }

    public function save(Client $client): void
    {
        $clients = $this->loadAll();
        $clients[] = $client;
        $this->saveAll($clients);
    }

    public function findByPin(string $pin): ?Client
    {
        $clients = $this->loadAll();
        foreach ($clients as $client) {
            if ($client->getPin() === $pin) {
                return $client;
            }
        }
        return null;
    }

    private function loadAll(): array
    {
        $data = file_get_contents($this->filePath);
        return $this->serializer->deserialize($data, Client::class . '[]', 'json');
    }

    private function saveAll(array $clients): void
    {
        $data = $this->serializer->serialize($clients, 'json');
        file_put_contents($this->filePath, $data);
    }
}
