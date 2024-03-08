<?php

namespace App\Services;

use App\Contracts\CurlServiceInterface;
use App\Exceptions\CurlException;

final class CurlService implements CurlServiceInterface
{

    public function __construct(private readonly array $defaultOptions = [])
    {
    }

    public function execute(string $requestMethod, string $url, array $parameters = [], array $extraOptions = []): string
    {

        $headers = [
            'Content-Type: application/json;charset=utf-8',
            'Accept: application/json',
        ];

        $curl = curl_init($url);

        switch ($requestMethod) {
            case 'GET':
                break;

            case 'POST':
                $parameters = !$parameters || !is_array($parameters)
                    ? '{}'
                    : json_encode($parameters);

                curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
                break;
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        foreach (array_replace($this->defaultOptions, $extraOptions) as $option => $value) {
            curl_setopt($curl, $option, $value);
        }

        $rawResult = curl_exec($curl);
        $rawResult = is_string($rawResult) ? trim($rawResult) : '';

        $info = curl_getinfo($curl);
        $info['errno'] = curl_errno($curl);
        $info['error'] = curl_error($curl);

        curl_close($curl);

        if ($info['errno']) {
            $error = $info['error'];
            throw new CurlException([$error]);
        }

        return $rawResult;
    }

}