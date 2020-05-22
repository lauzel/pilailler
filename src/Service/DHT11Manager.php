<?php

namespace App\Service;

use App\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DHT11Manager
{
    const HUMIDITY_KEY = 'humidity';
    const TEMPERATURE_KEY= 'temperature';
    /**
     * @var string
     */
    private $dir;

    public function __construct(KernelInterface $kernel)
    {
        $this->dir = $kernel->getProjectDir();
    }

    private function readSensor() : array
    {
        $process = new Process([
            'python',
            $this->dir.'/scripts/DHT11/DHT11.py'
        ]);

        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();

        $data = json_decode($output, true);

        if($data[self::HUMIDITY_KEY] == -999 || $data[self::TEMPERATURE_KEY] == -999) {
            throw new \Exception('Erreur lors de la lecture du DHT');
        }

        return $data;
    }

    public function read($maxTry = 5) : array {
        $try = 0;
        $data = [];

        while ($try < $maxTry) {
            try {
                $data = $this->readSensor();
                break;
            } catch (\Exception $e) {
                $try ++;
            }
        }

        if(empty($data)) {
            throw new \Exception('impossible de contacter le DHT');
        }

        return $data;
    }

    public function getTemperature() : int {
        $data = $this->read();

        return $data[self::TEMPERATURE_KEY];
    }

    public function getHumidity() {
        $data = $this->read();

        return $data[self::HUMIDITY_KEY];
    }
}
