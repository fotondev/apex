<?php

namespace App\Utils;

use App\Exceptions\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use function Symfony\Component\String\u;


final class Validator
{
    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new ValidationException(['email' => 'The email can not be empty.']);
        }

        if (null === u($email)->indexOf('@')) {
            throw new ValidationException(['email' => 'The email should look like a real email.']);
        }

        return $email;
    }

    public function validateName(?string $name): string
    {
        if (empty($name)) {
            throw new ValidationException(['name' => 'The name can not be empty.']);
        }

        if (1 !== preg_match('/^[a-z_]+$/', $name)) {
            throw new ValidationException(['name' => 'The name must contain only lowercase latin characters and underscores.']);
        }

        return $name;
    }

    public function validatePassword(?string $password): string
    {
        if (empty($password)) {
            throw new ValidationException(['password' => 'The password cannot be empty.']);
        }

        if (strlen($password) < 6) {
            throw new ValidationException(['password' => 'The password must be at least 6 characters long.']);
        }

        return $password;
    }


}