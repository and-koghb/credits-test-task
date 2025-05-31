<?php

namespace App\Domain\Credit;

class Credit
{
    public function __construct(
        private string $name,
        private float $amount,
        private float $rate,
        private \DateTimeInterface $startDate,
        private \DateTimeInterface $endDate,
        private string $clientPin
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function getClientPin(): string
    {
        return $this->clientPin;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }
}
