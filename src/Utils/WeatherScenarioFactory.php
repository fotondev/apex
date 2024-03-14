<?php

namespace App\Utils;

final class WeatherScenarioFactory
{

    private static array $descriptions = [
        'signature "anything can happen" with potentially high variability' => 1,
        'alternative with potentially more gradual variation' => 2,
        'guaranteed overcast (no rain, only changing clouds)' => 3,
        'guaranteed sunny (only minor cloud changes)' => 4,
        'predominantly overcast with potential rain' => 5,
        'guaranteed light-medium rain all weekend' => 6,
        'guaranteed medium-heavy rain' => 7,
    ];

    public static function create(int $scenarioId): array
    {
        switch ($scenarioId) {
            case 1:
                return [
                    'cloudLevel' => rand(25, 35) / 100,
                    'rain' => 0.2,
                    'weatherRandomness' => rand(5, 7),
                ];
            case 2:
                return [
                    'cloudLevel' => rand(45, 60) / 100,
                    'rain' => 0.0,
                    'weatherRandomness' => rand(5, 7),
                ];
            case 3:
                return [
                    'cloudLevel' => rand(60, 100) / 100,
                    'rain' => 0.0,
                    'weatherRandomness' => rand(1, 3),
                ];
            case 4:
                return [
                    'cloudLevel' => rand(0, 40) / 100,
                    'rain' => 0.0,
                    'weatherRandomness' => rand(1, 3),
                ];
            case 5:
                return [
                    'cloudLevel' => rand(60, 90) / 100,
                    'rain' => 0.0,
                    'weatherRandomness' => rand(4, 7),
                ];
            case 6:
                return [
                    'cloudLevel' => rand(60, 80) / 100,
                    'rain' => rand(10, 30) / 100,
                    'weatherRandomness' => rand(1, 3),
                ];
            case 7:
                return [
                    'cloudLevel' => rand(60, 100) / 100,
                    'rain' => rand(45, 80) / 100,
                    'weatherRandomness' => rand(1, 3),
                ];
            default:
                return [];
        }
    }

    public static function getScenarios(): array
    {
        return self::$descriptions;
    }


}