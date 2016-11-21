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
		$this->bicicleta = new Bicicleta(1234);
		$this->tarjeta = new Tarjeta(0001);
		$this->medioBoleto = new MedioBoleto();
		$this->paseLibre = new PaseLibre();
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
		
		$this->tarjeta->saldo = 100;
		$saldo_aux = $this->tarjeta->saldo();
		$this->assertEquals($saldo_aux, $this->tarjeta->saldo);
		
		$this->tarjeta->saldo = 0;
		$this->tarjeta->recargar(290);
		$this->assertEquals($this->tarjeta->saldo, 340);	
		
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->colectivo, "2016/06/30 22:50");
		$saldo_final = $saldo_inicial - 8.50;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo);
		
		$trasbordo = new Colectivo("135", "Rosario Bus");
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($trasbordo, "2016/06/30 23:10");
		$saldo_final = $saldo_inicial - 2.81;
		$this->assertEquals(round($saldo_final), round($this->tarjeta->saldo));
		
		$this->medioBoleto->recargar(290);
		$saldo_inicial = $this->medioBoleto->saldo();
		$this->medioBoleto->pagar($this->colectivo, "2016/06/30 23:10");
		$saldo_final = $saldo_inicial - 4.25;
		$this->assertEquals($saldo_final, $this->medioBoleto->saldo);
		
		$saldo_inicial = $this->paseLibre->saldo();
		$this->paseLibre->pagar($this->colectivo, "2016/06/30 23:10");
		$saldo_final = $saldo_inicial;
		$this->assertEquals($saldo_final, $this->paseLibre->saldo);
	
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->bicicleta, "2016/06/30 23:10");
		$saldo_final = $saldo_inicial - 12;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo);
		
		$this->tarjeta->viajes = 3;
		$viajes = $this->tarjeta->viajesRealizados();
		$this->assertEquals($viajes, $this->tarjeta->viajes);
	}
}

?>