<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $shortName = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $driverCategory = null;

    #[ORM\Column(length: 255)]
    private ?string $playerID = null;

    #[ORM\ManyToMany(targetEntity: Entry::class, mappedBy: 'drivers')]
    #[ORM\JoinTable(name: 'entries_drivers')]
    #[ORM\JoinColumn(name: 'driver_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'entry_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Collection $entries;

    #[ORM\OneToMany(targetEntity: EntriesDrivers::class, mappedBy: 'driver', cascade: ['persist'], orphanRemoval: true)]
    private Collection $entriesDrivers;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->entriesDrivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getDriverCategory(): ?int
    {
        return $this->driverCategory;
    }

    public function setDriverCategory(?int $driverCategory): static
    {
        $this->driverCategory = $driverCategory;

        return $this;
    }

    public function getPlayerID(): ?string
    {
        return $this->playerID;
    }

    public function setPlayerID(string $playerID): static
    {
        $this->playerID = $playerID;

        return $this;
    }

    /**
     * @return Collection<int, Entry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): static
    {
        if (!$this->entries->contains($entry)) {
            $this->entries->add($entry);
            $entry->addDriver($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): static
    {
        if ($this->entries->removeElement($entry)) {
            $entry->removeDriver($this);
        }

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
            $entriesDriver->setDriver($this);
        }

        return $this;
    }

    public function removeEntriesDriver(EntriesDrivers $entriesDriver): static
    {
        if ($this->entriesDrivers->removeElement($entriesDriver)) {
            // set the owning side to null (unless already changed)
            if ($entriesDriver->getDriver() === $this) {
                $entriesDriver->setDriver(null);
            }
        }

        return $this;
    }
}
