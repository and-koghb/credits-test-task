<?php

namespace App\Domain\Credit\Rules;

use App\Domain\Client\Client;
use App\Domain\Client\Region;
use App\Domain\Credit\Credit;

class ClientValidRegionRule implements CreditApprovalRule
{
    private array $allowedRegions;

    public function __construct()
    {
        $this->allowedRegions = Region::all();
    }

    public function isApproved(Client $client, Credit $credit): bool
    {
        return in_array($client->getRegion(), $this->allowedRegions, true);
    }

    public function getMessage(): string
    {
        return 'Client should not be outside from these regions: ' . implode(', ', $this->allowedRegions);
    }

    public function applyConditions(Client $client, Credit $credit): void {}
}
