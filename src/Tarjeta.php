<?php

namespace Nacho;

class Tarjeta implements InterfaceTarjeta {
	public $viajes = [];
	public $saldo = 0;
	public $descuento;
	public $boletos = [];
	public $ulti_bici = NULL;
	public $id;
	public $plus = 0;

	public function __construct($id) {
		$this->id = $id;
		$this->descuento = 1;
	}
	public function pagar(Transporte $transporte, $fechaHora) {
		if ($transporte->tipo() == "Colectivo") {
		    $monto = 0;
		    if ($this->saldo() >= 8.5 && $this->plus() == 0 || $this->descuento == 0) {
		      $trasbordo = $this->trasbordo($fechaHora, $transporte);
		      $monto = $this->costo($trasbordo);

		      $this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fechaHora));
		      $this->saldo -= $monto;
		      $this->boletos[] = new Boleto($this->id, $fechaHora, $transporte, $this->saldo, $monto, $this->tipo);
		    }
		    else if($this->saldo() >= 8.5 * ($this->plus()+1)) {
		      $monto = 8.5 * $this->plus();
		      $trasbordo = $this->trasbordo($fechaHora, $transporte);
		      $monto += $this->costo($trasbordo);
		      
		      $this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fechaHora));
		      $this->saldo -= $monto;
		      $this->boletos[] = new Boleto($this->id, $fechaHora, $transporte, $this->saldo, $monto, $this->plus." Plus + ".$this->tipo);
		    }
		    else if ($this->saldo() < 8.5 && $this->plus() < 2) {
		      $this->plus += 1;
		      $this->viajes[] = new Viaje($transporte->tipo(), "Plus ".$this->plus, $transporte, strtotime($fechaHora));
		      $this->boletos[] = new Boleto($this->id, $fechaHora, $transporte, $this->saldo, 0, $this->plus." Plus");
		    }
		    else {
		      echo("Error");
		    }
		} 
		else if ($transporte->tipo() == "Bicicleta") {
		    if($this->saldo() >= 12 && $this->plus()==0) {
		      if(date("Y-m-d", strtotime($fechaHora)) == date("Y-m-d", strtotime($this->ultimabici()))) {
		        $this->viajes[] = new Viaje($transporte->tipo(), 0, $transporte, strtotime($fechaHora));
		        $this->boletos[] = new Boleto($this->id, $fechaHora, $transporte, $this->saldo, 0, "Normal");
		      }
		      else {
		        $this->ulti_bici = $fechaHora;
		        $this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fechaHora));
		        $this->saldo -= 12;
		        $this->boletos[] = new Boleto($this->id, $fechaHora, $transporte, $this->saldo, 12, "Normal");
		      }
		    }
		    else {
		      echo ("Error");
			}
		}
	}

	public function costo($trasbordo) {
		$costo = 0;
		$this->plus = 0;
		if ($trasbordo) {
			$costo += 2.64 * $this->descuento;
			$this->tipo = "Trasbordo";
		}
		else {
		$costo += 8.5 * $this->descuento;
		if($this->descuento == 0.5) $this->tipo = "Medio";
			else $this->tipo = "Normal";
		}
		return $costo;
	}

	public function trasbordo($fechaHora, $transporte){
		$trasbordo = FALSE;
		if (count($this->viajes) > 0 && end($this->viajes)->transporte() != $transporte && end($this->viajes)->tipo() != "Trasbordo") {
			$auxH = date("H", end($this->viajes)->tiempo());
			$auxN = date("N", end($this->viajes)->tiempo());
			if($auxH > 22 || $auxH < 6){
				if(strtotime($fechaHora) - end($this->viajes)->tiempo() <= 5400)
					$trasbordo = TRUE;
			}
			else if($auxN == 6) {
				if($auxH < 22 && $auxH > 14){
					if(strtotime($fechaHora) - end($this->viajes)->tiempo() <= 5400)
					$trasbordo = TRUE;
				}
				else
				if(strtotime($fechaHora) - end($this->viajes)->tiempo() <= 3600)
					$trasbordo = TRUE;
			}
			else if($auxN == 7) {
				if(strtotime($fechaHora) - end($this->viajes)->tiempo() <= 5400)
					$trasbordo = TRUE;
			}
			else{
				if(strtotime($fechaHora) - end($this->viajes)->tiempo() <= 3600)
					$trasbordo = TRUE;
			}
		}
		return $trasbordo;
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

	public function ultimabici() {
   		return $this->ulti_bici;
  	}

	public function plus() {
		return $this->plus;
	}

	public function saldo() { 
		return $this->saldo; 
	}

	public function viajesRealizados() { 
		return $this->viajes; 
	}
}

