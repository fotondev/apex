<?php

namespace App\Services\Contracts;

use App\Entity\RaceEvent;

interface RaceManagerInterface
{
    public function createRaceEvent(): void;

    public function createSessions(): void;

    public function createSettings(): void;

}