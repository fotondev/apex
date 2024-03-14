<?php

namespace App\Command;

use App\Entity\Car;
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
    name: 'import-cars',
    description: 'Import cars from json to database',
)]
class ImportCarsCommand extends Command
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly EntityManagerInterface $entityManager

    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $carsJson = $this->params->get('app.data_dir') . '/cars.json';
        $cars = json_decode(file_get_contents($carsJson), true);

        foreach ($cars['vehicles'] as $item) {
            $car = new Car();
            $car->setCarModel($item['carModel']);
            $car->setYear($item['year']);
            $car->setFreeForAll($item['freeForAll']);
            $car->setGT3($item['gt3']);
            $car->setGT4($item['gt4']);
            $car->setGTC($item['gtc']);
            $car->setTCX($item['tcx']);

            $this->entityManager->persist($car);
        }

        $this->entityManager->flush();

        $output->writeln('Cars added to the database successfully.');


        return Command::SUCCESS;
    }
}
