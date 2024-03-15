<?php

namespace App\Entity;

use App\Repository\RaceSessionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RaceSessionRepository::class)]
class RaceSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(
        notInRangeMessage: 'Hour of day must be between {{ min }} and {{ max }}.',
        min: 0,
        max: 23
    )]
    private ?int $hourOfDay = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(
        notInRangeMessage: 'Day of weekend must be between {{ min }} and {{ max }}.',
        min: 1,
        max: 3
    )]
    private ?int $dayOfWeekend = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(
        notInRangeMessage: 'Time multiplier must be between {{ min }} and {{ max }}.',
        min: 0,
        max: 23
    )]
    private ?int $timeMultiplier = null;

    #[ORM\Column(length: 1)]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    #[Assert\Choice(
        choices: ['R', 'P', 'Q'],
        message: 'Session type must be one of {{ choices }}.'
    )]
    private ?string $sessionType = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    private ?int $sessionDurationMinutes = null;

    #[ORM\ManyToOne(targetEntity: RaceEvent::class, inversedBy: 'sessions')]
    #[JoinColumn(name: 'race_event_id', referencedColumnName: 'id')]
    #[Ignore]
    private RaceEvent $raceEvent;

    #[ORM\Column]
    #[Ignore]
    private ?int $raceEventId = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getHourOfDay(): ?int
    {
        return $this->hourOfDay;
    }

    public function setHourOfDay(int $hourOfDay): static
    {
        $this->hourOfDay = $hourOfDay;

        return $this;
    }

    public function getDayOfWeekend(): ?int
    {
        return $this->dayOfWeekend;
    }

    public function setDayOfWeekend(int $dayOfWeekend): static
    {
        $this->dayOfWeekend = $dayOfWeekend;

        return $this;
    }

    public function getTimeMultiplier(): ?int
    {
        return $this->timeMultiplier;
    }

    public function setTimeMultiplier(int $timeMultiplier): static
    {
        $this->timeMultiplier = $timeMultiplier;

        return $this;
    }

    public function getSessionType(): ?string
    {
        return $this->sessionType;
    }

    public function setSessionType(string $sessionType): static
    {
        $this->sessionType = $sessionType;

        return $this;
    }

    public function getSessionDurationMinutes(): ?int
    {
        return $this->sessionDurationMinutes;
    }

    public function setSessionDurationMinutes(int $sessionDurationMinutes): static
    {
        $this->sessionDurationMinutes = $sessionDurationMinutes;

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
