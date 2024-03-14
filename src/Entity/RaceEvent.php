<?php

namespace App\Entity;

use App\Repository\RaceEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RaceEventRepository::class)]
class RaceEvent
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[CustomAssert\TrackNameConstraint]
    private ?string $track = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(min: 30, max: 3600)]
    private ?int $preRaceWaitingTimeSeconds = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(min: 0, max: 3600)]
    private ?int $sessionOverTimeSeconds = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    private ?int $ambientTemp = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    private ?float $cloudLevel = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    private ?float $rain = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    private ?int $weatherRandomness = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    private ?int $configVersion = null;

    #[ORM\OneToMany(targetEntity: RaceSession::class, mappedBy: 'raceEvent', cascade: ['persist', 'remove'])]
    private Collection $sessions;

    #[ORM\Column(length: 16)]
    private ?string $type = null;

    #[ORM\OneToOne(targetEntity: Settings::class, mappedBy: 'raceEvent', cascade: ['persist', 'remove'])]
    private ?Settings $settings = null;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTrack(): ?string
    {
        return $this->track;
    }

    public function setTrack(string $track): static
    {
        $this->track = $track;

        return $this;
    }

    public function getPreRaceWaitingTimeSeconds(): ?int
    {
        return $this->preRaceWaitingTimeSeconds;
    }

    public function setPreRaceWaitingTimeSeconds(int $preRaceWaitingTimeSeconds): static
    {
        $this->preRaceWaitingTimeSeconds = $preRaceWaitingTimeSeconds;

        return $this;
    }

    public function getSessionOverTimeSeconds(): ?int
    {
        return $this->sessionOverTimeSeconds;
    }

    public function setSessionOverTimeSeconds(int $sessionOverTimeSeconds): static
    {
        $this->sessionOverTimeSeconds = $sessionOverTimeSeconds;

        return $this;
    }

    public function getAmbientTemp(): ?int
    {
        return $this->ambientTemp;
    }

    public function setAmbientTemp(int $ambientTemp): static
    {
        $this->ambientTemp = $ambientTemp;

        return $this;
    }

    public function getCloudLevel(): ?float
    {
        return $this->cloudLevel;
    }

    public function setCloudLevel(float $cloudLevel): static
    {
        $this->cloudLevel = $cloudLevel;

        return $this;
    }

    public function getRain(): ?float
    {
        return $this->rain;
    }

    public function setRain(float $rain): static
    {
        $this->rain = $rain;

        return $this;
    }

    public function getWeatherRandomness(): ?int
    {
        return $this->weatherRandomness;
    }

    public function setWeatherRandomness(int $weatherRandomness): static
    {
        $this->weatherRandomness = $weatherRandomness;

        return $this;
    }

    public function getConfigVersion(): ?int
    {
        return $this->configVersion;
    }

    public function setConfigVersion(int $configVersion): static
    {
        $this->configVersion = $configVersion;

        return $this;
    }

    /**
     * @return Collection<int, RaceSession>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(RaceSession $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setRaceEvent($this);
        }

        return $this;
    }

    public function removeSession(RaceSession $session): static
    {
        if ($this->sessions->removeElement($session)) {
            if ($session->getRaceEvent() === $this) {
                $session->setRaceEvent(null);
            }
        }

        return $this;
    }

    public function getType(): string
    {
        return $this->type;

    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings): static
    {
        $this->settings = $settings;
        $settings->setRaceEvent($this);

        return $this;
    }
}
