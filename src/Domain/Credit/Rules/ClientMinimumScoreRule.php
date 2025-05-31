<?php

namespace App\Domain\Credit\Rules;

use App\Domain\Client\Client;
use App\Domain\Credit\Credit;

class ClientMinimumScoreRule implements CreditApprovalRule
{
    public function isApproved(Client $client, Credit $credit): bool
    {
        return $client->getScore() > 500;
    }

    public function getMessage(): string
    {
        return 'Credit score should not be less than 500';
    }

    public function applyConditions(Client $client, Credit $credit): void {}
}

