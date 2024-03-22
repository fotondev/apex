<?php

namespace App\Controller\Api;


use App\Entity\Track;
use App\Services\Contracts\RaceManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


final class BaseApiAction extends AbstractController
{
    protected static string $dataDir;


    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ParameterBagInterface  $parameterBag,
        private readonly SerializerInterface    $serializer,
        private readonly RaceManagerInterface   $raceManager,
    )
    {

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

    #[Route('/api/entries/save', name: 'api_entries_save', methods: 'POST')]
    public function saveEntries(Request $request)
    {

    }



}