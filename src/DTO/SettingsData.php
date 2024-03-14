<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
final class SettingsData
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        public readonly string $serverName,

        #[Assert\Type(type: 'string')]
        public readonly string $adminPassword,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        public readonly string $carGroup,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $trackMedalsRequirement,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $safetyRatingRequirement,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $racecraftRatingRequirement,


        #[Assert\Type(type: 'string')]
        public readonly string $password,

        #[Assert\Type(type: 'string')]
        public readonly string $spectatorPassword,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\Range(min: 1, max: 30)]
        public readonly int $maxCarSlots,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $dumpLeaderboards,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $isRaceLocked,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $randomizeTrackWhenEmpty,

        #[Assert\Type(type: 'string')]
        public readonly string $centralEntryListPath,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $allowAutoDQ,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $shortFormationLap,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $dumpEntryList,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int $formationLapType
    ) {
    }
}