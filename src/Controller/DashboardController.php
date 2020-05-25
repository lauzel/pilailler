<?php

namespace App\Controller;

use App\Command\CheckTankCommand;
use App\Entity\TankStats;
use App\Repository\MetricsRepository;
use App\Repository\TankStatsRepository;
use App\Service\DHT11Manager;
use App\Service\TankFillingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(MetricsRepository $repository, TankStatsRepository $tankStatsRepository)
    {
        $lastMetric = $repository->findOneBy([], [
           'created_at' => 'DESC'
        ]);

        $metrics = $repository->findBy([], [
            'created_at' => 'ASC'
        ]);

        $tankFilling  = $tankStatsRepository->findOneBy([], [
            'createdAt' => 'DESC'
        ]);

        return $this->render('dashboard.html.twig', [
            'lastMetric' => $lastMetric,
            'metrics' => $metrics,
            'tankFilling' => $tankFilling->getFillingPercent()
        ]);
    }

    /**
     * @Route("/dashboard/refreshtankstat", name="refreshtankdashboard")
     */
    public function refreshTankStat(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => CheckTankCommand::getDefaultName(),
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        return $this->redirectToRoute('dashboard');
    }
}
