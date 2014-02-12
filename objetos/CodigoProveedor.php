<?php

class CodigoProveedor extends Elemento {

	protected $idproveedor;
	protected $idarticulo;
	protected $codigo_proveedor;
	protected static $tabla = 'codigo_proveedor';

	public function getIdproveedor() { return $this->idproveedor; }
	public function setIdproveedor($idproveedor) { $this->idproveedor = $idproveedor; }
	public function getIdarticulo() { return $this->idarticulo; }
	public function setIdarticulo($idarticulo) { $this->idarticulo = $idarticulo; }
	public function getCodigoProveedor() { return $this->codigo_proveedor; }
	public function setCodigoProveedor($codigo_proveedor) { $this->codigo_proveedor = $codigo_proveedor; }

}

?>