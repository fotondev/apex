<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private ?string $carGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    #[ORM\Column]
    private ?int $maxCarSlots = null;

    #[ORM\Column]
    private ?int $raceEventId = null;

    #[ORM\OneToOne(targetEntity: RaceEvent::class, inversedBy: 'settings')]
    #[JoinColumn(name: 'race_event_id', referencedColumnName: 'id')]
    #[Ignore]
    private ?RaceEvent $raceEvent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarGroup(): ?string
    {
        return $this->carGroup;
    }

    public function setCarGroup(string $carGroup): static
    {
        $this->carGroup = $carGroup;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getMaxCarSlots(): ?int
    {
        return $this->maxCarSlots;
    }

    public function setMaxCarSlots(int $maxCarSlots): static
    {
        $this->maxCarSlots = $maxCarSlots;

        return $this;
    }

    public function getRaceEventId(): ?int
    {
        return $this->raceEventId;

    }

    public function setRaceEventId(int $raceEventId): static
    {
        $this->raceEventId = $raceEventId;

        return $this;

    }

    public function getRaceEvent(): ?RaceEvent
    {
        return $this->raceEvent;

    }

    public function setRaceEvent(RaceEvent $raceEvent): static
    {
        $this->raceEvent = $raceEvent;

        return $this;

    }
}
