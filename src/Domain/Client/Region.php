<?php

namespace App\Domain\Client;

enum Region: string
{
    case PRAGUE = 'PR';
    case BRNO = 'BR';
    case OSTRAVA = 'OS';

    public static function all(): array
    {
        $regions = [];
        foreach (Region::cases() as $case) {
            $regions[] = $case->value;
        }

        return $regions;
    }
}
