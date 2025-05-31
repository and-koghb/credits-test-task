<?php

namespace App\DTO;

use App\Validators\ExistingPin;
use Symfony\Component\Validator\Constraints as Assert;

class ApplyForCreditRequest
{
    #[Assert\NotBlank]
    #[ExistingPin]
    public string $pin;

    #[Assert\NotBlank]
    #[Assert\Length(max: 155)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9.\-%\- ]*$/u',message: 'Name can only contain letters, numbers, dots, hyphens, spaces and % .')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Range(min: 100, max: 999999999)]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 50)]
    public float $rate;

    #[Assert\NotBlank]
    #[Assert\Date]
    #[Assert\Callback(['App\Validators\DateRangeValidator', 'validateStartDate'])]
    public string $startDate;

    #[Assert\NotBlank]
    #[Assert\Date]
    #[Assert\Callback(['App\Validators\DateRangeValidator', 'validateEndDate'])]
    public string $endDate;
}
