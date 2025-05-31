<?php

namespace App\Validators;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class UniquePin extends Constraint
{
    public string $message = 'There already is a user with the provided PIN.';

    public function validatedBy(): string
    {
        return UniquePinValidator::class;
    }
}
