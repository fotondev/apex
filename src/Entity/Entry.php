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

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $forcedCarModel = null;

    #[ORM\Column]
    private ?bool $overrideDriverInfo = null;

    #[ORM\Column]
    private ?bool $isServerAdmin = null;

    #[ORM\ManyToMany(targetEntity: Driver::class, inversedBy: 'entries')]
    #[ORM\JoinTable(name: 'entries_drivers')]
    #[ORM\JoinColumn(name: 'entry_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'driver_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Collection $drivers;

    #[ORM\ManyToOne(inversedBy: 'entries')]
    #[ORM\JoinColumn(name: 'race_event_id', referencedColumnName: 'id')]
    private ?RaceEvent $raceEvent = null;

    #[ORM\Column]
    #[Ignore]
    private ?int $raceEventId = null;

    #[ORM\OneToMany(targetEntity: EntriesDrivers::class, mappedBy: 'entry', cascade: ['persist'], orphanRemoval: true)]
    private Collection $entriesDrivers;

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
        $this->entriesDrivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getForcedCarModel(): ?int
    {
        return $this->forcedCarModel;
    }

    public function setForcedCarModel(int $forcedCarModel): static
    {
        $this->forcedCarModel = $forcedCarModel;

        return $this;
    }

    public function isOverrideDriverInfo(): ?bool
    {
        return $this->overrideDriverInfo;
    }

    public function setOverrideDriverInfo(bool $overrideDriverInfo): static
    {
        $this->overrideDriverInfo = $overrideDriverInfo;

        return $this;
    }

    public function isIsServerAdmin(): ?bool
    {
        return $this->isServerAdmin;
    }

    public function setIsServerAdmin(bool $isServerAdmin): static
    {
        $this->isServerAdmin = $isServerAdmin;

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

    /**
     * @return Collection<int, EntriesDrivers>
     */
    public function getEntriesDrivers(): Collection
    {
        return $this->entriesDrivers;
    }

    public function addEntriesDriver(EntriesDrivers $entriesDriver): static
    {
        if (!$this->entriesDrivers->contains($entriesDriver)) {
            $this->entriesDrivers->add($entriesDriver);
            $entriesDriver->setEntry($this);
        }

        return $this;
    }

    public function removeEntriesDriver(EntriesDrivers $entriesDriver): static
    {
        if ($this->entriesDrivers->removeElement($entriesDriver)) {
            // set the owning side to null (unless already changed)
            if ($entriesDriver->getEntry() === $this) {
                $entriesDriver->setEntry(null);
            }
        }

        return $this;
    }
}
