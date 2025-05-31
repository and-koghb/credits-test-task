<?php

namespace App\Validators;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ValidRegion extends Constraint
{
    public string $message = 'Invalid region. Allowed regions are: {{ regions }}.';

    public function validatedBy(): string
    {
        return ValidRegionValidator::class;
    }
}
