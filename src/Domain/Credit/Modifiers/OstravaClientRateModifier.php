<?php

namespace App\Domain\Credit\Modifiers;

use App\Domain\Client\Client;
use App\Domain\Client\Region;
use App\Domain\Credit\Credit;

class OstravaClientRateModifier implements CreditModifier
{
    public function modify(Credit $credit, Client $client): void
    {
        if ($client->getRegion() === Region::OSTRAVA->value) {
            $credit->setRate($credit->getRate() + 5);
        }
    }
}
