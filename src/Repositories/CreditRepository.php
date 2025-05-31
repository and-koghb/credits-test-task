<?php

namespace App\Repositories;

use App\Domain\Credit\Credit;
use Symfony\Component\Serializer\SerializerInterface;

class CreditRepository
{
    private string $filePath;

    public function __construct(
        string $storageDirectory,
        private SerializerInterface $serializer
    ) {
        $this->filePath = $storageDirectory . '/credits.json';
        $this->initializeFile();
    }

    public function save(Credit $credit): void
    {
        $credits = $this->loadAll();
        $credits[] = $credit;
        file_put_contents($this->filePath, $this->serializer->serialize($credits, 'json'));
    }

    public function exists(Credit $credit): bool
    {
        $credits = $this->loadAll();
        foreach ($credits as $existing) {
            if ($this->isSameCredit($existing, $credit)) {
                return true;
            }
        }
        return false;
    }

    private function isSameCredit(Credit $a, Credit $b): bool
    {
        return $a->getClientPin() === $b->getClientPin()
            && $a->getAmount() === $b->getAmount()
            && $a->getRate() === $b->getRate()
            && $a->getStartDate() == $b->getStartDate()
            && $a->getEndDate() == $b->getEndDate();
    }

    private function loadAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        return $this->serializer->deserialize(
            file_get_contents($this->filePath),
            Credit::class . '[]',
            'json'
        );
    }

    private function initializeFile(): void
    {
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, '[]');
        }
    }
}
