<?php

namespace App\Domain\Credit;

class ApplicationDecision
{
    public function __construct(
        private bool $isApproved,
        private float $finalRate,
        private array $rejectionReasons = []
    ) {}

    public function toArray(): array
    {
        return [
            'approved' => $this->isApproved,
            'final_rate' => $this->finalRate,
            'messages' => $this->rejectionReasons,
            'timestamp' => time()
        ];
    }

    public function isApproved(): bool
    {
        return $this->isApproved;
    }

    public function getFinalRate(): float
    {
        return $this->finalRate;
    }

    public function getRejectionReasons(): array
    {
        return $this->rejectionReasons;
    }
}
