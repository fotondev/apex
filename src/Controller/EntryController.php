<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EntryController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/admin/race-event/{id}/entries', name: 'app_race_event_entries', methods: 'GET')]
    public function index(int $id): Response
    {
        $entries = $this->em->getRepository(Entry::class)->findBy(['raceEventId' => $id]);
        $forms = [];

        foreach ($entries as $entry) {
            $form = $this->createForm(EntryType::class, $entry);
            $form->add("saveEntry", SubmitType::class);
            $forms[] = $form->createView();
        }

        return $this->render('entry/index.html.twig', [
            'forms' => $forms,
        ]);
    }


    #[Route('/admin/entries/{id}', name: 'app_entry_save', methods: 'POST')]
    public function saveEntry(Request $request, int $id)
    {
        $entry = $this->em->getRepository(Entry::class)->find($id);
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($entry);
            $this->em->flush();
        }
        return $this->redirectToRoute('app_race_event_entries', ['id' => $entry->getRaceEventId()]);

    }


    #[Route('/admin/entries/{id}', name: 'app_entry_delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $entry = $this->em->getRepository(Entry::class)->find($id);

        if (!$entry) {
            return $this->json(['message' => 'Entry not found'], 404);
        }
        $em = $this->em;
        $em->remove($entry);
        $em->flush();
        return $this->json(['message' => 'Entry deleted']);
    }


}
