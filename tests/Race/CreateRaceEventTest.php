<?php

namespace App\Tests\Race;

use App\Entity\RaceEvent;
use App\Services\Contracts\RaceManagerInterface;
use App\Services\Race\CreateRaceEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateRaceEventTest extends KernelTestCase
{
    private RaceManagerInterface $raceManager;
    private RaceEvent $raceEvent;
    private string $testJson;


    protected function setUp(): void
    {
        self::bootKernel();
        $this->raceManager = $this->getContainer()->get(RaceManagerInterface::class);
        $this->testJson = file_get_contents($this->getContainer()->getParameter('app.data_dir') . '/events.json');
    }

    public function testCreateRaceEvent(): void
    {
        $dataArr = json_decode($this->testJson, true);

        $raceEvent = $this->raceManager->execute($dataArr);
        $this->raceEvent = $raceEvent;
        $session = $this->raceEvent->getSessions()[0];


        $this->assertInstanceOf(RaceEvent::class, $raceEvent);
        $this->assertEquals('mount_panorama', $raceEvent->getTrack());
        $this->assertEquals($session->getRaceEvent(), $this->raceEvent);

    }


}
