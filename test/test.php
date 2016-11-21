<?php

require __DIR__ . '/../vendor/autoload.php';

use Nacho\Bicicleta;
use Nacho\Colectivo;
use Nacho\InterfaceTarjeta;
use Nacho\MedioBoleto;
use Nacho\PaseLibre;
use Nacho\Tarjeta;
use Nacho\Transporte;
use Nacho\Viaje;
use Nacho\Boleto;
use PHPUnit\Framework\TestCase;

class TransporteTest extends TestCase {
	public $transporte;
	public $viaje;
	public $colectivo;
	public $patente;

	public function setUp() {
		$this->transporte = new Transporte();
		$this->viaje = new Viaje("Colectivo", 8.50, "144 Negro", "27/09/16 14:44");
		$this->colectivo = new Colectivo("144 Negro", "Rosario Bus");
		$this->trolebus = new Colectivo("K", "Semtur");
		$this->bicicleta = new Bicicleta(1234);
		$this->tarjeta = new Tarjeta(0001);
		$this->medioBoleto = new MedioBoleto();
		$this->paseLibre = new PaseLibre();
		$this->boleto = new Boleto("0001", "2016-09-30 03:30:00", $this->colectivo, 20, 8.5, "Normal");
	}

	public function testBoleto() {
		$this->assertEquals($this->boleto->id(), "0001");
		$this->assertEquals($this->boleto->fecha(), "2016-09-30 03:30:00");
		$this->assertEquals($this->boleto->transporte(), "144 Negro");
		$this->assertEquals($this->boleto->saldo(), 20);
		$this->assertEquals($this->boleto->monto(), 8.5);
		$this->assertEquals($this->boleto->tipo(), "Normal");
	} 

	public function testTransporte() {
		$this->transporte->tipo = "Colectivo";
		$type = $this->transporte->tipo();
		$this->assertEquals($type, $this->transporte->tipo);
	}

	public function testViaje() {
		
		$tipo = $this->viaje->tipo();
		$this->assertEquals($tipo, "Colectivo");
		
		$monto = $this->viaje->monto();
		$this->assertEquals($monto, 8.50);
		
		$transporte = $this->viaje->transporte();
		$this->assertEquals($transporte, "144 Negro");
		
		$tiempo = $this->viaje->tiempo();
		$this->assertEquals($tiempo, "27/09/16 14:44");
	}
	
	public function testColectivo() {
		$nombre = $this->colectivo->nombre();
		$this->assertEquals($nombre, "144 Negro");
	}
	
	public function testBicicleta() {
		$patente = $this->bicicleta->nombre();
		$this->assertEquals($patente, 1234);
	}

	public function testTarjeta() {
		$aux_saldo = 0;
		$this->tarjeta->recargar(290);
		$this->assertEquals($this->tarjeta->saldo(),$aux_saldo + 340);
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->recargar(544);
		$this->assertEquals($this->tarjeta->saldo(),$aux_saldo + 680);
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->recargar(30);
		$this->assertEquals($this->tarjeta->saldo(),$aux_saldo + 30);

		$this->tarjeta->pagar($this->colectivo, "2016-10-10 13:30:00");
		foreach ($this->tarjeta->viajesRealizados() as $viaje) {
			$this->assertEquals($viaje->tipo(), "Colectivo");
			$this->assertEquals($viaje->monto(), 8.5);
			$this->assertEquals($viaje->transporte()->nombre(), "144 Negro");
			$this->assertEquals($viaje->tiempo(), strtotime("2016-10-10 13:30:00"));
		}

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->pagar($this->colectivo, "2016-10-10 13:30:00");
		$this->assertEquals($this->tarjeta->saldo(),0);
		$this->assertEquals($this->tarjeta->plus(),1);

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->recargar(20);
		$this->tarjeta->pagar($this->colectivo, "2016-10-10 13:30:00");
		$this->tarjeta->pagar($this->trolebus, "2016-10-10 13:50:00");
		$this->assertEquals($this->tarjeta->saldo(), 8.86);

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->pagar($this->colectivo, "2016-10-10 13:30:00");
		$this->tarjeta->recargar(20);
		$this->tarjeta->pagar($this->colectivo, "2016-10-10 18:30:00");
		$this->assertEquals($this->tarjeta->saldo(),3);
		$this->assertEquals($this->tarjeta->plus(),0);

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->pagar($this->colectivo, "2016-10-10 13:30:00");
		$this->tarjeta->pagar($this->colectivo, "2016-10-11 13:30:00");
		$this->tarjeta->pagar($this->colectivo, "2016-10-12 13:30:00");
		$this->expectOutputString("Error");

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->recargar(50);
		$this->tarjeta->pagar($this->bicicleta, "2016-10-10 13:30:00");
		$this->tarjeta->pagar($this->bicicleta, "2016-10-11 13:30:00");
		$this->assertEquals($this->tarjeta->saldo(),26);

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->recargar(50);
		$this->tarjeta->pagar($this->bicicleta, "2016-10-10 13:30:00");
		$this->tarjeta->pagar($this->bicicleta, "2016-10-10 18:30:00");
		$this->assertEquals($this->tarjeta->saldo(),38);

		$this->tarjeta->saldo = 0;
		$this->tarjeta->plus = 0;
		$this->tarjeta->viajes = array();
		$this->tarjeta->boletos = array();
		$this->tarjeta->ulti_bici = NULL;
		$aux_saldo = $this->tarjeta->saldo();

		$this->tarjeta->pagar($this->bicicleta, "2016-10-10 13:30:00");
		$this->expectOutputString("ErrorError");
	}
}

?>