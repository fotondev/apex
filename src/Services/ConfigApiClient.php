<?php

namespace App\Services;

use App\Services\Contracts\CurlServiceInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ConfigApiClient
{
    const EVENT_DEFAULT_CONFIG_URL = "";
    const RACE_CONFIG_URL = "";
    const RACE_CONFIG_SAVE_URL = "";
    public static string $configDir;

    public function __construct(
        private readonly CurlServiceInterface  $curlService,
        private readonly ParameterBagInterface $bag,
        private readonly SerializerInterface   $serializer
    )
    {
        self::$configDir = $bag->get('app.data_dir');
    }

    public function fetchEventDefaultConfiguration()
    {
        $errors = null;
        $payload = [];
        $url = "";
        $payload = $this->curlService->execute('GET', $url);

        return $payload;
    }


    public function readJsonFile(string $file)
    {
        return json_decode(file_get_contents(self::$configDir . "/" . $file), true);
    }

    public function saveRaceConfig(array $config, array $defaultSettings)
    {
        $dir = self::$configDir . "/" . $config['id'];
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        foreach ($config['settings'] as $key => $value) {
            if (array_key_exists($key, $defaultSettings)) {
                $defaultSettings[$key] = $value;
            }
        }

        $excludedKeys = ['settings', 'type', 'raceEventId'];
        $raceConfigData = array_diff_key($config, array_flip($excludedKeys));

        $raceConfig = $this->serializer->serialize($raceConfigData, 'json');
        $serverConfig = $this->serializer->serialize($defaultSettings, 'json');
        file_put_contents($dir . "/race.json", $raceConfig);
        file_put_contents($dir . "/settings.json", $serverConfig);

    }

}