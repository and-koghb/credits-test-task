<?php

namespace App\Validators;

use App\Domain\Client\Region;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidRegionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidRegion) {
            return;
        }

        $validValues = [];
        foreach (Region::cases() as $case) {
            $validValues[] = $case->value;
        }

        if (!in_array($value, $validValues, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ regions }}', implode(', ', $validValues))
                ->addViolation();
        }
    }
}
