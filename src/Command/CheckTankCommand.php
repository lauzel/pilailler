<?php

namespace App\Command;

use App\Entity\Metrics;
use App\Entity\TankStats;
use App\Service\DHT11Manager;
use App\Service\TankFillingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckTankCommand extends Command
{
    protected static $defaultName = 'app:checktank';
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var TankFillingManager
     */
    private $tankFillingManager;

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('mock', null, InputOption::VALUE_OPTIONAL, 'Option description')
        ;
    }

    public function __construct(EntityManagerInterface $manager, TankFillingManager $tankFillingManager)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->tankFillingManager = $tankFillingManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if($input->getOption('mock')) {
            $data = rand(10,200);
        } else {
            $data = $this->tankFillingManager->getFillingPercent();
        }

        $metric = new TankStats();
        $metric->setCreatedAt(new \DateTime('now'));
        $metric->setFillingPercent($data);

        $this->manager->persist($metric);
        $this->manager->flush();

        $io->success('TanksStats inserted in database');

        return 0;
    }
}
