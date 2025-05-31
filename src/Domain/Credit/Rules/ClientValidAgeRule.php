<?php

namespace App\Domain\Credit\Rules;

use App\Domain\Client\Client;
use App\Domain\Credit\Credit;

class ClientValidAgeRule implements CreditApprovalRule
{
    public function isApproved(Client $client, Credit $credit): bool
    {
        return $client->getAge() >= 18 && $client->getAge() <= 60;
    }

    public function getMessage(): string
    {
        return 'Client age should not be less than 18 years and grater than 60 years';
    }

    public function applyConditions(Client $client, Credit $credit): void {}
}
