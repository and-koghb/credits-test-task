<?php

namespace App\Domain\Credit\Modifiers;

use App\Domain\Client\Client;
use App\Domain\Credit\Credit;

interface CreditModifier
{
    public function modify(Credit $credit, Client $client): void;
}
