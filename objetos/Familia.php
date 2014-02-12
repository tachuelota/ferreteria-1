<?php

class Familia extends Elemento {

	protected $nombre;
	protected static $tabla = 'familia';

	public function getNombre() { return $this->nombre; }
	public function setNombre($nombre) { $this->nombre = $nombre; }

}

?>