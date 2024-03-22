<?php

namespace App\Controller;

use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DriverController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
//
//    #[Route('/driver', name: 'app_driver')]
//    public function index(): Response
//    {
//        return $this->render('driver/index.html.twig', [
//            'controller_name' => 'DriverController',
//        ]);
//    }


    #[Route('/admin/drivers/{id}', name: 'app_driver_delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $driver = $this->em->getRepository(Driver::class)->find($id);
        if (!$driver) {
            return $this->json(['message' => 'Driver not found'], 404);
        }
        $em = $this->em;
        $em->remove($driver);
        $em->flush();
        return $this->json(['message' => 'Driver deleted']);

    }
}
