<?php

namespace Nacho;

class Tarjeta implements InterfaceTarjeta {
	public $viajes = [];
	public $saldo = 0;
	public $descuento;

	public function __construct() {
		$this->descuento = 1;
	}

	public function pagar(Transporte $transporte, $fechaHora) {
		if ($transporte->tipo() == "Colectivo") {
			$trasbordo = false;
			if (count($this->viajes) > 0) {
				if (end($this->viajes)->tiempo() - strtotime($fechaHora) < 3600) {
					$trasbordo = true;
				}
			}

			$monto = 0;
			if ($trasbordo) {
				$monto = 2.81 * $this->descuento;
			}
			else {
				$monto = 8.50 * $this->descuento;
			}

			$this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fechaHora));
			$this->saldo -= $monto;
		} 
		else if ($transporte->tipo() == "Bicicleta") {
			$this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fechaHora));
			$this->saldo -= 12;
		}
	}

	public function recargar($monto) {
		if ($monto == 290) {
			$this->saldo += 340;
		}
		else if ($monto == 544) {
			$this->saldo += 680;
		}
		else {
			$this->saldo += $monto;
		}
	}

	public function saldo() { 
		return $this->saldo; 
	}

	public function viajesRealizados() { 
		return $this->viajes; 
	}
}

