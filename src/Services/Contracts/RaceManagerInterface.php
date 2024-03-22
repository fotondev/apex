<?php

namespace App\Services\Contracts;

use App\Entity\Entry;

interface RaceManagerInterface
{
    public function createRaceEvent(): void;

    public function createSessions(): void;

    public function createSettings(): void;

    public function createDefaultEntry(array $entry): Entry;

}