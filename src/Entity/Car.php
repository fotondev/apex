<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $carModel = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?bool $freeForAll = null;

    #[ORM\Column]
    private ?bool $gt3 = null;

    #[ORM\Column]
    private ?bool $gt4 = null;

    #[ORM\Column]
    private ?bool $gtc = null;

    #[ORM\Column]
    private ?bool $tcx = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarModel(): ?string
    {
        return $this->carModel;
    }

    public function setCarModel(string $carModel): static
    {
        $this->carModel = $carModel;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function isFreeForAll(): ?bool
    {
        return $this->freeForAll;
    }

    public function setFreeForAll(bool $freeForAll): static
    {
        $this->freeForAll = $freeForAll;

        return $this;
    }

    public function isGT3(): ?bool
    {
        return $this->gt3;
    }

    public function setGT3(bool $gt3): static
    {
        $this->gt3 = $gt3;

        return $this;
    }

    public function isGT4(): ?bool
    {
        return $this->gt4;
    }

    public function setGT4(bool $gt4): static
    {
        $this->gt4 = $gt4;

        return $this;
    }

    public function isGTC(): ?bool
    {
        return $this->gtc;
    }

    public function setGTC(bool $gtc): static
    {
        $this->gtc = $gtc;

        return $this;
    }

    public function isTCX(): ?bool
    {
        return $this->tcx;
    }

    public function setTCX(bool $tcx): static
    {
        $this->tcx = $tcx;

        return $this;
    }
}
