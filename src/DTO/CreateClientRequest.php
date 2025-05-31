<?php

namespace App\DTO;

use App\Domain\Client\Region;
use App\Validators\UniquePin;
use App\Validators\ValidRegion;
use Symfony\Component\Validator\Constraints as Assert;

class CreateClientRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 155)]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s\.\-]+$/", message: "Name can only contain letters, spaces, dots and hyphens.")]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Range(min: 18, max: 60)]
    public int $age;

    #[Assert\NotBlank]
    #[ValidRegion]
    public string $region;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Assert\LessThanOrEqual(999999999)]
    public float $income;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Assert\LessThanOrEqual(1000)]
    public int $score;

    #[Assert\NotBlank]
    #[Assert\Length(max: 12)]
    #[Assert\Regex(pattern: "/^[\d\-]+$/",message: "PIN can only contain numbers and hyphens.")]
    #[UniquePin]
    public string $pin;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\+420\d{9}$/",message: "Phone must start with +420 followed by 9 digits.")]
    public string $phone;
}
