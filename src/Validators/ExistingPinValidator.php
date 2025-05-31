<?php

namespace App\Validators;

use App\Repositories\ClientRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistingPinValidator extends ConstraintValidator
{
    public function __construct(private ClientRepository $clientRepository) {}

    public function validate($value, Constraint $constraint)
    {
        if (!$value || !$this->clientRepository->findByPin($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
