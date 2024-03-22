<?php

namespace App\Entity;

use App\Repository\EntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $raceNumber = null;

    #[ORM\ManyToMany(targetEntity: Driver::class, inversedBy: 'entries', cascade: ['persist', 'remove'])]
    private Collection $drivers;

    #[ORM\ManyToOne(inversedBy: 'entries')]
    #[ORM\JoinColumn(name: 'race_event_id', referencedColumnName: 'id')]
    #[Ignore]
    private ?RaceEvent $raceEvent = null;

    #[ORM\Column]
    #[Ignore]
    private ?int $raceEventId = null;

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getRaceNumber(): ?int
    {
        return $this->raceNumber;
    }

    public function setRaceNumber(int $raceNumber): static
    {
        $this->raceNumber = $raceNumber;

        return $this;
    }

    /**
     * @return Collection<int, Driver>
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): static
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers->add($driver);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): static
    {
        $this->drivers->removeElement($driver);

        return $this;
    }

    public function getRaceEvent(): ?RaceEvent
    {
        return $this->raceEvent;
    }

    public function setRaceEvent(?RaceEvent $raceEvent): static
    {
        $this->raceEvent = $raceEvent;

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
}

