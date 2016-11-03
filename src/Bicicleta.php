<?php

namespace Nacho;

class Bicicleta extends Transporte {
	public $patente;

	public function __construct($patente) {
		$this->tipo = "Bicicleta";
		$this->patente = $patente;
	}

	public function nombre() {
		return $this->patente;
	}
}