<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\RaceEvent;
use App\Entity\RaceSession;
use App\Entity\Settings;
use App\Entity\Track;
use App\Exceptions\ValidationException;
use App\Form\QuickRaceType;
use App\Form\RaceEventType;
use App\Services\ConfigApiClient;
use App\Services\Contracts\RaceManagerInterface;
use App\Services\ServerManager;
use App\Utils\Race;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RaceEventController extends AbstractController
{
    private array $tracks;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RaceManagerInterface   $raceManager,
        private readonly ConfigApiClient        $client,
        private readonly SerializerInterface    $serializer
    )
    {
        $this->tracks = $this->em->getRepository(Track::class)->getAllTrackNamesWithIds();
    }

    #[Route('/race-events', name: 'app_race_events',  methods: ['GET'])]
    public function index(): Response
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select(
            'r.id',
            't.name as track_name',
            't.country as track_country',
            't.uniquePitboxes as track_unique_pitboxes',
            'r.preRaceWaitingTimeSeconds',
            'r.ambientTemp as ambient_temp',
            'COUNT(DISTINCT entry.id) as entry_count',
        )
            ->addSelect('MAX(CASE WHEN rs.sessionType = :practice THEN rs.sessionDurationMinutes ELSE 0 END) AS practice_duration')
            ->addSelect('MAX(CASE WHEN rs.sessionType = :race THEN rs.sessionDurationMinutes ELSE 0 END) AS race_duration')
            ->addSelect('MAX(CASE WHEN rs.sessionType = :qualifying THEN rs.sessionDurationMinutes ELSE 0 END) AS qualifying_duration')
            ->addSelect('MAX(CASE WHEN rs.sessionType = :practice THEN rs.hourOfDay ELSE 0 END) AS practice_start')
            ->from(RaceEvent::class, 'r')
            ->leftJoin(Track::class, 't', 'WITH', 'r.track = t.id')
            ->leftJoin(RaceSession::class, 'rs', 'WITH', 'r.id = rs.raceEventId')
            ->leftJoin(Settings::class, 'sett', 'WITH', 'r.id = sett.raceEventId')
            ->leftJoin(Entry::class, 'entry', 'WITH', 'r.id = entry.raceEventId')
            ->where('r.type = :type')
            ->groupBy('r.id', 't.name', 't.country', 't.uniquePitboxes', 'r.preRaceWaitingTimeSeconds')
            ->orderBy('r.id', 'DESC')
            ->setParameter('type', Race::RACE_WEEKEND)
            ->setParameter('practice', Race::PRACTICE)
            ->setParameter('qualifying', Race::QUALIFY)
            ->setParameter('race', Race::RACE)
            ->orderBy('r.id', 'DESC');

        $raceEvents = $qb->getQuery()->getScalarResult();

        return $this->render('raceEvent/index.html.twig', [
            'race_events' => $raceEvents,
        ]);
    }

    #[Route('/admin/race-event', name: 'app_race_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $configFile = '/event.json';
        $settingsFile = '/settings.json';
        $formType = RaceEventType::class;
        $formTemplate = 'raceEvent/edit.html.twig';

        if ($request->query->get('quick-race')) {
            $formType = QuickRaceType::class;
            $configFile = '/quick_race.json';
            $formTemplate = 'raceEvent/quick_race_edit.html.twig';

        }

        $raceEventData = $this->client->readJsonFile($configFile);
        $defaultSettings = $this->client->readJsonFile($settingsFile);

        $data = ['race_event' => $raceEventData, 'settings' => $defaultSettings];
        try {
            $raceEvent = $this->raceManager->execute($data);

        } catch (ValidationException $e) {
            $errors = $e->errors;
            return $this->render('errors/badConfig.html.twig', ['errors' => $errors]);
        }
        $form = $this->createForm($formType, $raceEvent, ['tracks' => $this->tracks]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($raceEvent);

            $formData = $this->serializer->normalize($form->getData(), 'json');
            $this->client->saveRaceConfig($formData, $defaultSettings);

            if ($form->getName() == 'quick_race') {
                ServerManager::startServer();
                return $this->render('raceEvent/quick_race_show.html.twig', ['raceEvent' => $raceEvent]);
            }

            $this->em->flush();
            return $this->redirectToRoute('app_race_events');
        }

        return $this->render($formTemplate, ['form' => $form->createView()]);
    }

    #[Route('/admin/race-event/{id}', name: 'app_race_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $raceEvent = $this->em->getRepository(RaceEvent::class)->find($request->get('id'));

        if (!$raceEvent) {
            throw $this->createNotFoundException();
        }

        $defaultSettings = $this->client->readJsonFile("/{$raceEvent->getId()}/settings.json");

        $form = $this->createForm(RaceEventType::class, $raceEvent, ['tracks' => $this->tracks]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($raceEvent);
            $this->em->flush();

            $formData = $this->serializer->normalize($form->getData(), 'json');

            $this->client->saveRaceConfig($formData, $defaultSettings);

            return $this->redirectToRoute('app_race_events');
        }
        return $this->render('raceEvent/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/race-event/delete/{id}', name: 'app_race_event_delete', methods: 'DELETE')]
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
