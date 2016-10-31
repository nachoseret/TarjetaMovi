<?php

namespace Nacho;

class MedioBoleto extends Tarjeta {
	public function __construct() {
		$this->descuento = 0.5;
	}	
}