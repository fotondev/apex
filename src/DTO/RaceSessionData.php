<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


final class RaceSessionData
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\Range(
            notInRangeMessage: 'Hour of day must be between {{ min }} and {{ max }}.',
            min: 0,
            max: 23
        )]
        public readonly int $hourOfDay,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\Range(
            notInRangeMessage: 'Day of weekend must be between {{ min }} and {{ max }}.',
            min: 1,
            max: 3
        )]
        public readonly int $dayOfWeekend,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\Range(
            notInRangeMessage: 'Time multiplier must be between {{ min }} and {{ max }}.',
            min: 0,
            max: 23
        )]
        public readonly int $timeMultiplier,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        #[Assert\Choice(
            choices: ['R', 'P', 'Q'],
            message: 'Session type must be one of {{ choices }}.'
        )]
        public readonly string $sessionType,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $sessionDurationMinutes
    )
    {
    }
}