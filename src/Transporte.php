<?php

namespace Nacho;

class Transporte {
	protected $tipo;

	public function setTipo($t){
		$this->tipo = $t;
	}

	public function tipo() {
		return $this->tipo;
	}
}