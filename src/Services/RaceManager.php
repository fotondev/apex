<?php

namespace App\Services;

use App\DTO\EntryData;
use App\DTO\RaceData;
use App\DTO\RaceSessionData;
use App\DTO\SettingsData;
use App\Entity\Entry;
use App\Entity\RaceEvent;
use App\Entity\RaceSession;
use App\Entity\Settings;
use App\Services\Contracts\RaceManagerInterface;

class RaceManager extends BaseService implements RaceManagerInterface
{
    private RaceData $source;
    private array $data;

    public function execute(array $data): RaceEvent
    {
        $this->data = $data;
        $this->source = self::createFromArray($data['race_event'], RaceData::class);
        $this->validate($this->source);
        $this->createRaceEvent();
        $this->createSettings();
        return $this->raceEvent;
    }

    public function createRaceEvent(): void
    {
        $source = $this->source;
        $this->raceEvent = (new RaceEvent())
            ->setTrack($source->track)
            ->setPreRaceWaitingTimeSeconds($source->preRaceWaitingTimeSeconds)
            ->setSessionOverTimeSeconds($source->sessionOverTimeSeconds)
            ->setAmbientTemp($source->ambientTemp)
            ->setCloudLevel($source->cloudLevel)
            ->setRain($source->rain)
            ->setRain($source->rain)
            ->setWeatherRandomness($source->weatherRandomness)
            ->setConfigVersion($source->configVersion);
        $this->createSessions();

    }

    public function createSessions(): void
    {
        array_map(function ($session) {
            $dto = self::createFromArray($session, RaceSessionData::class);
            $this->validate($dto);
            $raceSession = (new RaceSession());
            $raceSession
                ->setHourOfDay($dto->hourOfDay)
                ->setDayOfWeekend($dto->dayOfWeekend)
                ->setTimeMultiplier($dto->timeMultiplier)
                ->setSessionType($dto->sessionType)
                ->setSessionDurationMinutes($dto->sessionDurationMinutes);
            $this->raceEvent->addSession($raceSession);
            return $raceSession;
        }, $this->source->sessions);
    }


    public function createSettings(): void
    {
        $dto = self::createFromArray($this->data['settings'], SettingsData::class);
        $this->validate($dto);
        $settings = (new Settings())
            ->setCarGroup($dto->carGroup)
            ->setPassword($dto->password)
            ->setMaxCarSlots($dto->maxCarSlots);
        $this->raceEvent->setSettings($settings);
    }


    public function createDefaultEntry(array $entry): Entry
    {
        $dto = self::createFromArray($entry, EntryData::class);
        $this->validate($dto);
        $entry = (new Entry())
            ->setRaceNumber($dto->raceNumber)
            ->setForcedCarModel($dto->forcedCarModel)
            ->setOverrideDriverInfo($dto->overrideDriverInfo)
            ->setIsServerAdmin($dto->isServerAdmin);
        foreach ($dto->drivers as $driver) {
            $entry->addDriver($driver);
        }
        return $entry;
    }

}
