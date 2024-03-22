<?php

namespace App\Services;

final class ServerManager
{

    public static function startServer(): void
    {
        $command = 'start "" "C:\Program Files (x86)\Steam\steamapps\common\Assetto Corsa Competizione Dedicated Server\server\accServer.exe"';
        exec($command, $output, $returnVar);
        echo "Server started.";
    }

}