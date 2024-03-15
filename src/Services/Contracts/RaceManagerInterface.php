<?php

namespace App\Services\Contracts;

interface RaceManagerInterface
{
    public function createRaceEvent(): void;

    public function createSessions(): void;

    public function createSettings(): void;

}