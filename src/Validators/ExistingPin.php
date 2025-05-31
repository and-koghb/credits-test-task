<?php

namespace App\Validators;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ExistingPin extends Constraint
{
    public string $message = 'There is not a client with the provided PIN.';

    public function validatedBy(): string
    {
        return ExistingPinValidator::class;
    }
}
