<?php

namespace App\Services;

use App\DTO\SettingsData;
use App\Entity\RaceEvent;
use App\Entity\ServerSettings;
use App\Entity\Settings;
use App\Exceptions\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseService
{

    public RaceEvent $raceEvent;
    public Settings $settings;


    public function __construct(
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface    $serializer
    )
    {
    }

    public function validate(object $source): void
    {
        if ($source instanceof SettingsData) {
            return;
        }
        $errors = $this->validator->validate($source);
        if ($errors->count() > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new ValidationException($errorMessages);
        }
    }

    public static function createFromArray(array $data, string $classname): object
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->denormalize($data, $classname);
    }


    public function save(): void
    {
        $this->em->persist($this->raceEvent);
    }

}
