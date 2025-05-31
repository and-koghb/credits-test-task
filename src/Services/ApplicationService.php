<?php

namespace App\Services;

use App\Domain\Credit\ApplicationDecision;

class ApplicationService
{
    private string $storagePath;

    public function __construct(string $storageDirectory)
    {
        $this->storagePath = rtrim($storageDirectory, '/') . '/applications.json';
        $this->ensureFileExists();
    }

    public function saveDecision(string $key, ApplicationDecision $decision): void
    {
        $data = $this->loadAll();
        $data[$key] = $decision->toArray();
        file_put_contents($this->storagePath, json_encode($data));
    }

    public function getDecision(string $key): ?ApplicationDecision
    {
        $data = $this->loadAll();
        if (!isset($data[$key])) {
            return null;
        }

        return new ApplicationDecision(
            $data[$key]['approved'],
            $data[$key]['final_rate'],
            $data[$key]['messages'] ?? []
        );
    }

    private function loadAll(): array
    {
        return json_decode(file_get_contents($this->storagePath), true) ?: [];
    }

    private function ensureFileExists(): void
    {
        if (!file_exists($this->storagePath)) {
            file_put_contents($this->storagePath, '{}');
        }
    }
}
