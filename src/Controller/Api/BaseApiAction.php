<?php

namespace App\Controller\Api;

use App\Entity\Track;
use App\Services\BaseService;
use App\Services\Contracts\RaceManagerInterface;
use App\Services\Race\RaceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


final class BaseApiAction
{

    protected static array $errors = [];
    protected static int $statusCode = Response::HTTP_OK;
    protected static string $dataDir;
    protected EntityManagerInterface $em;
    protected ParameterBagInterface $parameterBag;

    protected SerializerInterface $serializer;

    protected RaceManagerInterface $raceManager;

    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface  $parameterBag,
        SerializerInterface    $serializer,
        RaceManagerInterface   $raceManager
    )
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
        $this->serializer = $serializer;
        $this->raceManager = $raceManager;

        self::$dataDir = $parameterBag->get('app.data_dir');
    }


    #[Route('/api/settings/max-car-slots', name: 'api_settings_max_car_slots', methods: 'GET')]
    public function getTrackMaxCarSlots(Request $request): JsonResponse
    {
        $errors = [];
        $code = Response::HTTP_OK;
        $trackId = $request->query->get('trackId');
        if (!$trackId) {
            $errors = 'Track ID is required';
            $code = Response::HTTP_BAD_REQUEST;
        }
        $slots = $this->em->getRepository(Track::class)->find($trackId)->getUniquePitboxes();

        return new JsonResponse(['errors' => $errors, 'slots' => $slots], $code);
    }


}