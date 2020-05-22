<?php
namespace App\Service;

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\InputPinInterface;

class DHT11Manager {

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

        



        return ["cul", "lait"];
	}

}
