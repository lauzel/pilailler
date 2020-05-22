<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DHT11Manager;

class TestDHT11Controller extends AbstractController
{
    /**
     * @Route("/test", name="test_d_h_t11")
     */
    public function index(DHT11Manager $manager)
    {
        return $this->json($manager->read());
    }
}
