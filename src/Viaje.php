<?php

namespace Nacho;

class Viaje {
	public $tipo;
	public $monto;
	public $transporte;
	public $tiempo;

	public function __construct($tipo, $monto, $transporte, $tiempo) {
		$this->tipo = $tipo;
		$this->monto = $monto;
		$this->transporte = $transporte;
		$this->tiempo = $tiempo;
	}

	public function tipo() {
		return $this->tipo;
	}

	public function monto() {
		return $this->monto;
	}

	public function transporte() {
		return $this->transporte;
	}

	public function tiempo() {
		return $this->tiempo;
	}
}