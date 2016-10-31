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
use PHPUnit\Framework\TestCase;

class TransporteTest extends TestCase {
	public $transporte;
	public $viaje;
	public function setUp() {
		$this->transporte = new Transporte();
		$this->transporte->setTipo("Colectivo");
		$this->viaje = new Viaje("Colectivo", 8.50, "144 Negro", "27/09/16 14:44");
	}
	//Test Class Transporte
	public function testTransporte() {
		$type = $this->transporte->tipo();
		$this->assertEquals($type, $this->transporte->tipo());
	}
	//Test Class Viaje
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
}

?>