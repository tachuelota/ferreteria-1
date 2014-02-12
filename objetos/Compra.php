<?php

class Compra extends Elemento {

	protected $fecha;
	protected $idencargado;
	protected $observaciones;
	protected $articulos;
	protected static $tabla = 'compra';

	public function getFecha() { return $this->fecha; }
	public function setFecha($fecha) { $this->fecha = $fecha; }
	public function getIdencargado() { return $this->idencargado; }
	public function setIdencargado($idencargado) { $this->idencargado = $idencargado; }
	public function getObservaciones() { return $this->observaciones; }
	public function setObservaciones($observaciones) { $this->observaciones = $observaciones; }
	public function getArticulos() { return $this->articulos; }
	public function setArticulos($articulos) { $this->articulos = $articulos; }

}

?>