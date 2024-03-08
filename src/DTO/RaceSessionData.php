<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


final class RaceSessionData
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $hourOfDay,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $dayOfWeekend,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $timeMultiplier,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        public readonly string $sessionType,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $sessionDurationMinutes
    )
    {
    }
}