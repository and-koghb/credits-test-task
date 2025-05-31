<?php

namespace App\Domain\Credit\Rules;

use App\Domain\Client\Client;
use App\Domain\Credit\Credit;

class ClientMinimumIncomeRule implements CreditApprovalRule
{
    public function isApproved(Client $client, Credit $credit): bool
    {
        return $client->getIncome() >= 1000;
    }

    public function getMessage(): string
    {
        return 'Monthly should not be less than $1000';
    }

    public function applyConditions(Client $client, Credit $credit): void {}
}
