<?php
namespace App\Service;

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;

class DHT11Manager {

        const ERROR_TIMEOUT = -2;

	private $gpio;

	public function __construct() {
	 	$this->gpio = new GPIO();
	}

	public function read() {

                $pinNumber = 11;
                $bits = [0,0,0,0,0];

                $pin = $this->gpio->getOutputPin($pinNumber);
                $pin->setValue(PinInterface::VALUE_LOW);    
                usleep(18000); // 18ms
                $pin->setValue(PinInterface::VALUE_HIGH);

                $pin = $this->gpio->getInputPin($pinNumber);

                $time = microtime();
                $loopCnt = 100; // 100u

                while($pin->getValue() == PinInterface::VALUE_LOW) {
                        if((microtime() - $time) > $loopCnt) {
                                dump('LOW');
                                return self::ERROR_TIMEOUT;
                        }

                }               


                return ["cul", "lait"];
	}

}
