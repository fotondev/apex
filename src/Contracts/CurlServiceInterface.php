<?php

namespace App\Contracts;

interface CurlServiceInterface
{
    public function execute(
        string $requestMethod,
        string $url,
        array  $parameters = [],
        array  $extraOptions = []
    ): string;
}