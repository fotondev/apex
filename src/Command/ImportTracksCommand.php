<?php

namespace App\Command;

use App\Entity\Car;
use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'import-tracks',
    description: 'Add a short description for your command',
)]
class ImportTracksCommand extends Command
{
    public function __construct(
        private readonly ParameterBagInterface  $params,
        private readonly EntityManagerInterface $entityManager

    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $tracksJson = $this->params->get('app.data_dir') . '/tracks.json';
        $tracks = json_decode(file_get_contents($tracksJson), true);

        foreach ($tracks as $item) {
            $track = new Track();
            $track->setId($item['id']);
            $track->setName($item['name']);
            $track->setCountry($item['country']);
            $track->setUniquePitboxes($item['unique_pit_boxes']);
            $track->setPrivateServerSlots($item['private_server_slots']);
            $this->entityManager->persist($track);
        }

        $this->entityManager->flush();

        $output->writeln('Tracks added to the database successfully.');


        return Command::SUCCESS;
    }
}
