<?php

namespace App\Entity;

use App\Repository\EntriesDriversRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntriesDriversRepository::class)]
class EntriesDrivers
{

    #[ORM\Id]
    #[ORM\Column(name: 'entry_id', type: 'integer', nullable: false)]
    private ?int $entryId = null;

    #[ORM\Id]
    #[ORM\Column(name: 'driver_id', type: 'integer', nullable: false)]
    private ?int $driverId = null;

    #[ORM\ManyToOne(targetEntity: Entry::class, cascade: ['persist'], inversedBy: 'entriesDrivers')]
    #[ORM\JoinColumn(name: 'entry_id', referencedColumnName: 'id')]
    private ?Entry $entry = null;

    #[ORM\ManyToOne(targetEntity: Driver::class, cascade: ['persist'], inversedBy: 'entriesDrivers')]
    #[ORM\JoinColumn(name: 'driver_id', referencedColumnName: 'id')]
    private ?Driver $driver = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntry(): ?Entry
    {
        return $this->entry;
    }

    public function setEntry(?Entry $entry): static
    {
        $this->entry = $entry;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getEntryId(): ?int
    {
        return $this->entryId;
    }

    public function setEntryId(int $entryId): static
    {
        $this->entryId = $entryId;

        return $this;
    }

    public function getDriverId(): ?int
    {
        return $this->driverId;
    }

    public function setDriverId(int $driverId): static
    {
        $this->driverId = $driverId;

        return $this;
    }
}
