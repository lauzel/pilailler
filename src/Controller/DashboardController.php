<?php

namespace App\Controller;

use App\Repository\MetricsRepository;
use App\Service\DHT11Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(MetricsRepository $repository)
    {
        $lastMetric = $repository->findOneBy([], [
           'created_at' => 'DESC'
        ]);

        $metrics = $repository->findBy([], [
            'created_at' => 'ASC'
        ]);

        return $this->render('dashboard.html.twig', [
            'lastMetric' => $lastMetric,
            'metrics' => $metrics
        ]);
    }
}
