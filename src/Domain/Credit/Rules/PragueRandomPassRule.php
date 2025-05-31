<?php

namespace App\Domain\Credit\Rules;

use App\Domain\Client\Client;
use App\Domain\Client\Region;
use App\Domain\Credit\Credit;

class PragueRandomPassRule implements CreditApprovalRule
{
    public function isApproved(Client $client, Credit $credit): bool
    {
        if ($client->getRegion() === Region::PRAGUE->value) {
            return rand(1, 2) == 1;
        }
        return true;
    }

    public function getMessage(): string
    {
        return 'You have been rejected.';
    }

    public function applyConditions(Client $client, Credit $credit): void {}
}
