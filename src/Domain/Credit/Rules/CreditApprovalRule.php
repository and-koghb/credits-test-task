<?php

namespace App\Domain\Credit\Rules;

use App\Domain\Client\Client;
use App\Domain\Credit\Credit;

interface CreditApprovalRule
{
    public function isApproved(Client $client, Credit $credit): bool;

    public function getMessage(): string;

    public function applyConditions(Client $client, Credit $credit): void;
}
