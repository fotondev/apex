<?php

namespace App\Controller;

use App\Entity\RaceEvent;
use App\Entity\Track;
use App\Exceptions\ValidationException;
use App\Form\RaceEventType;
use App\Services\ConfigApiClient;
use App\Services\Contracts\RaceManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RaceEventController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RaceManagerInterface   $raceManager,
        private readonly ConfigApiClient        $client,
        private readonly SerializerInterface    $serializer
    )
    {
    }

    #[Route('/race-events', name: 'app_race_events')]
    public function index(): Response
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('r.id', 't.name', 't.country')
            ->from(RaceEvent::class, 'r')
            ->leftJoin(Track::class, 't', 'WITH', 'r.track = t.id')
            ->orderBy('r.id', 'DESC');

        $raceEvents = $qb->getQuery()->getResult();

        return $this->render('raceEvent/index.html.twig', [
            'race_events' => $raceEvents,
        ]);
    }

    #[Route('/race-event', name: 'app_race_event_new')]
    public function new(Request $request): Response
    {
        $raceEventData = $this->client->readJsonFile('/event.json');
        $defaultSettings = $this->client->readJsonFile('/settings.json');
        $data = [
            'raceEvent' => $raceEventData,
            'settings' => $defaultSettings
        ];

        try {
            $raceEvent = $this->raceManager->execute($data);
        } catch (ValidationException $e) {
            $errors = $e->errors;
            return $this->render('errors/badConfig.html.twig', ['errors' => $errors]);
        }

        $tracks = $this->em->getRepository(Track::class)->getAllTrackNamesWithIds();

        $form = $this->createForm(RaceEventType::class, $raceEvent, ['tracks' => $tracks]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($raceEvent);
            $this->em->flush();

            $formData = $this->serializer->normalize($form->getData(), 'json');

            $this->client->saveRaceConfig($formData, $defaultSettings);

            return $this->redirectToRoute('app_race_events');
        }

        return $this->render('raceEvent/edit.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/race-event/{id}', name: 'app_race_event_edit')]
    public function edit(Request $request): Response
    {

        $raceEvent = $this->em->getRepository(RaceEvent::class)->find($request->get('id'));

        if (!$raceEvent) {
            throw $this->createNotFoundException();
        }

        $tracks = $this->em->getRepository(Track::class)->getAllTrackNamesWithIds();
        $settings = json_decode(file_get_contents($this->client::$configDir . '/settings.json'), true);

        $form = $this->createForm(RaceEventType::class, $raceEvent, ['tracks' => $tracks, 'settings' => $settings]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em->clear();
            $this->validateForm($form);
            return $this->redirectToRoute('app_race_events');
        }
        return $this->render('raceEvent/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/race-event/delete/{id}', name: 'app_race_event_delete')]
    public function delete(int $id): Response
    {
        $raceEvent = $this->em->getRepository(RaceEvent::class)->find($id);
        if (!$raceEvent) {
            throw $this->createNotFoundException();
        }
        $this->em->remove($raceEvent);
        $this->em->flush();

        $this->addFlash('success', 'Race event deleted successfully');

        return $this->redirectToRoute('app_race_events');
    }

}
