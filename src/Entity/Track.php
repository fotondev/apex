<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $country = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $uniquePitboxes = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $privateServerSlots = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getUniquePitboxes(): ?int
    {
        return $this->uniquePitboxes;
    }

    public function setUniquePitboxes(int $uniquePitboxes): static
    {
        $this->uniquePitboxes = $uniquePitboxes;

        return $this;
    }

    public function getPrivateServerSlots(): ?int
    {
        return $this->privateServerSlots;
    }

    public function setPrivateServerSlots(int $privateServerSlots): static
    {
        $this->privateServerSlots = $privateServerSlots;

        return $this;
    }

}

