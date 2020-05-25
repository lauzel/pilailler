<?php

namespace App\Command;

use App\Entity\Metrics;
use App\Service\DHT11Manager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MetricsCommand extends Command
{
    protected static $defaultName = 'app:metrics';

    /**
     * @var DHT11Manager
     */
    private $dht11Manager;
    /**
     * @var EntityManager
     */
    private $manager;

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('mock', null, InputOption::VALUE_OPTIONAL, 'Mock options')
        ;
    }

    public function __construct(DHT11Manager $DHT11Manager, EntityManagerInterface $manager)
    {
        $this->dht11Manager = $DHT11Manager;

        parent::__construct();
        $this->manager = $manager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if($input->getOption('mock')) {
            $data = [
                'humidity' => rand(60, 80),
                'temperature' => rand(18,30)
            ];
        } else {
            $data = $this->dht11Manager->read();
        }

        $metric = new Metrics();
        $metric->setCreatedAt(new \DateTime('now'));
        $metric->setHumidity($data['humidity']);
        $metric->setTemperature($data['temperature']);

        $this->manager->persist($metric);
        $this->manager->flush();

        $io->success('Metrics inserted in database');

        return 0;
    }
}
