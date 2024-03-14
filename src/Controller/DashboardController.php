<?php

namespace App\Controller;

use App\Entity\RaceEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {

        return $this->render('dashboard/index.html.twig', [

        ]);
    }
}
