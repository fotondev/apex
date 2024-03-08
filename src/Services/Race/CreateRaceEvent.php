<?php

namespace App\Services\Race;

use App\DTO\RaceData;
use App\DTO\RaceSessionData;
use App\Entity\RaceEvent;
use App\Entity\RaceSession;
use App\Services\BaseService;

class CreateRaceEvent extends BaseService
{

    public function execute(array $data): RaceEvent
    {
        $dto = $this->serializer->denormalize($data, RaceData::class);
        $this->validate($dto);
        $raceEvent = new RaceEvent();
        return $this->createRaceEvent($raceEvent, $dto);
    }

    public function createRaceEvent(RaceEvent $raceEvent, RaceData $raceData): RaceEvent
    {
        $raceEvent->setTrack($raceData->track);
        $raceEvent->setPreRaceWaitingTimeSeconds($raceData->preRaceWaitingTimeSeconds);
        $raceEvent->setSessionOverTimeSeconds($raceData->sessionOverTimeSeconds);
        $raceEvent->setAmbientTemp($raceData->ambientTemp);
        $raceEvent->setCloudLevel($raceData->cloudLevel);
        $raceEvent->setRain($raceData->rain);
        $raceEvent->setRain($raceData->rain);
        $raceEvent->setWeatherRandomness($raceData->weatherRandomness);
        $raceEvent->setConfigVersion($raceData->configVersion);
        $this->addSessions($raceEvent, $raceData->sessions);

        return $raceEvent;
    }


    public function addSessions(RaceEvent $raceEvent, $sessionsData): void
    {
        foreach ($sessionsData as $sessionItem) {

            $sessionDto = $this->serializer->denormalize($sessionItem, RaceSessionData::class);
            $this->validate($sessionDto);
            $raceSession = new RaceSession();
            $raceSession->setHourOfDay($sessionItem['hourOfDay']);
            $raceSession->setDayOfWeekend($sessionItem['dayOfWeekend']);
            $raceSession->setTimeMultiplier($sessionItem['timeMultiplier']);
            $raceSession->setSessionType($sessionItem['sessionType']);
            $raceSession->setSessionDurationMinutes($sessionItem['sessionDurationMinutes']);
            $raceEvent->addSession($raceSession);
        }
    }
}
