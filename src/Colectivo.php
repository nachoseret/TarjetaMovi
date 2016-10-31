<?php

namespace Nacho;

class Colectivo extends Transporte {
	protected $nombre;
	protected $linea;

	public function __construct($nombre, $linea) {
		$this->tipo = "Colectivo";
		$this->nombre = $nombre;
		$this->linea = $linea;
	}

	public function nombre() {
		return $this->nombre;
	}
	
}