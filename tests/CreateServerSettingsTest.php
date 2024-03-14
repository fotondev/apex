<?php

namespace App\Tests;

use App\Entity\RaceEvent;
use App\Entity\ServerSettings;
use App\Exceptions\ValidationException;
use App\Services\Race\CreateServerSettings;
use App\Services\Race\ServerManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use TypeError;

class CreateServerSettingsTest extends TestCase
{
    
    /**
     * @dataProvider getMockSettings
     */
    public function testCreateServerSettings(array $data): void
    {

        $manager = new ServerManager();
        $settings = $testCommand->execute($data);

        $this->assertInstanceOf(ServerSettings::class, $settings);

        $this->assertEquals($data["serverName"], $settings->getServerName());


    }


    public function getMockSettings(): \Generator
    {
        yield [
            [
                "serverName" => "APEX Server",
                "adminPassword" => "",
                "carGroup" => "FreeForAll",
                "trackMedalsRequirement" => -1,
                "safetyRatingRequirement" => -1,
                "racecraftRatingRequirement" => -1,
                "password" => "",
                "maxCarSlots" => 30,
                "spectatorPassword" => "",
                "configVersion" => 1,
                "dumpLeaderboards" => 0,
                "isRaceLocked" => 1,
                "randomizeTrackWhenEmpty" => 0,
                "centralEntryListPath" => "",
                "allowAutoDQ" => 1,
                "shortFormationLap" => 0,
                "dumpEntryList" => 0,
                "formationLapType" => 3
            ]
        ];

        yield [
            [
                "serverName" => "APEX Server 2",
                "adminPassword" => "",
                "carGroup" => "FreeForAll",
                "trackMedalsRequirement" => -1,
                "safetyRatingRequirement" => -1,
                "racecraftRatingRequirement" => -1,
                "password" => "",
                "maxCarSlots" => 30,
                "spectatorPassword" => "",
                "configVersion" => 1,
                "dumpLeaderboards" => 0,
                "isRaceLocked" => 1,
                "randomizeTrackWhenEmpty" => 0,
                "centralEntryListPath" => "",
                "allowAutoDQ" => 1,
                "shortFormationLap" => 0,
                "dumpEntryList" => 0,
                "formationLapType" => 3
            ]
        ];
    }
}
