<?php

namespace App\DTO;

use ArrayAccess;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Traversable;
use App\Validator as RaceAssert;

final class RaceData
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        #[RaceAssert\TrackNameConstraint]
        public readonly string $track,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\Range(min: 30, max: 3600)]
        public readonly int    $preRaceWaitingTimeSeconds,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\Range(min: 0, max: 3600)]
        public readonly int    $sessionOverTimeSeconds,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int    $ambientTemp,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'float')]
        public readonly float  $cloudLevel,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'float')]
        public readonly float  $rain,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int    $weatherRandomness,

        #[Assert\NotBlank]
        #[Assert\Valid]
        public readonly array  $sessions,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int    $configVersion
    )
    {
    }
}