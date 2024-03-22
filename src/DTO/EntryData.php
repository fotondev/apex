<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class EntryData
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int  $raceNumber,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        public readonly int  $forcedCarModel,
        #[Assert\Type(type: 'bool')]
        public readonly bool $overrideDriverInfo,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'bool')]
        public readonly bool $isServerAdmin,
        #[Assert\Valid]
        public readonly array $drivers,
    )
    {
    }


}