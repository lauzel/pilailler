<?php

namespace App\Controller;

use App\Entity\TankStats;
use App\Repository\MetricsRepository;
use App\Repository\TankStatsRepository;
use App\Service\DHT11Manager;
use App\Service\TankFillingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function refreshTankStat(TankFillingManager $tankFillingManager, EntityManagerInterface $manager)
    {
        try {

            $percent = $tankFillingManager->getFillingPercent();

            $data = new TankStats();
            $data->setCreatedAt(new \DateTime('now'));
            $data->setFillingPercent($percent);

            $manager->commit($data);
            $manager->flush();
        } catch (\Exception $e) {

        }

        return $this->redirectToRoute('dashboard');

    }
}
