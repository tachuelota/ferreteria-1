<?php

class Cliente extends Elemento {

	protected $rfc;
	protected $razon_social;
	protected $calle;
	protected $numero_exterior;
	protected $numero_interior;
	protected $codigo_postal;
	protected $colonia;
	protected $estado;
	protected $municipio;
	protected $nombre_contacto;
	protected $telefono;
	protected $email;
	protected static $tabla = 'cliente';

	public function getRfc() { return $this->rfc; }
	public function setRfc($rfc) { $this->rfc = $rfc; }
	public function getRazonSocial() { return $this->razon_social; }
	public function setRazonSocial($razon_social) { $this->razon_social = $razon_social; }
	public function getCalle() { return $this->calle; }
	public function setCalle($calle) { $this->calle = $calle; }
	public function getNumeroExterior() { return $this->numero_exterior; }
	public function setNumeroExterior($numero_exterior) { $this->numero_exterior = $numero_exterior; }
	public function getNumeroInterior() { return $this->numero_interior; }
	public function setNumeroInterior($numero_interior) { $this->numero_interior = $numero_interior; }
	public function getCodigoPostal() { return $this->codigo_postal; }
	public function setCodigoPostal($codigo_postal) { $this->codigo_postal = $codigo_postal; }
	public function getColonia() { return $this->colonia; }
	public function setColonia($colonia) { $this->colonia = $colonia; }
	public function getEstado() { return $this->estado; }
	public function setEstado($estado) { $this->estado = $estado; }
	public function getMunicipio() { return $this->municipio; }
	public function setMunicipio($municipio) { $this->municipio = $municipio; }
	public function getNombreContacto() { return $this->nombre_contacto; }
	public function setNombreContacto($nombre_contacto) { $this->nombre_contacto = $nombre_contacto; }
	public function getTelefono() { return $this->telefono; }
	public function setTelefono($telefono) { $this->telefono = $telefono; }
	public function getEmail() { return $this->email; }
	public function setEmail($email) { $this->email = $email; }

}

?>