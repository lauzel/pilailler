<?php


namespace App\Service;


use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TankFillingManager
{
    const DISTANCE_KEY = 'distance';

    /**
     * @var string
     */
    private $dir;

    public function __construct(KernelInterface $kernel)
    {
        $this->dir = $kernel->getProjectDir();
    }

    private function readSensor() : float
    {
        $process = new Process([
            'python',
            $this->dir.'/scripts/UltrasonicRanging.py'
        ]);

        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();

        $data = json_decode($output, true);

        if($data[self::DISTANCE_KEY] == -999) {
            throw new \Exception('Erreur lors de la lecture de la distance');
        }

        return $data[self::DISTANCE_KEY];
    }

    public function getFillingPercent() {
        $total = 200;
        $distance = $this->readSensor();

        $fillPercent = ($total - $distance)  * 100 / $total;

        return $fillPercent;
    }

}