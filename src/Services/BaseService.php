<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseService
{

    protected ValidatorInterface $validator;
    protected SerializerInterface $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    abstract public function execute(array $data): object;

    public function validate(object $object): void
    {
        $errors = $this->validator->validate($object);
        if ($errors->count() > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new ValidationException($errorMessages);
        }
    }

}
