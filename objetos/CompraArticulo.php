<?php

class CompraArticulo extends Elemento {

	protected $idcompra;
	protected $idarticulo;
	protected $cantidad;
	protected static $tabla = 'compra_articulo';

	public function getIdcompra() { return $this->idcompra; }
	public function setIdcompra($idcompra) { $this->idcompra = $idcompra; }
	public function getIdarticulo() { return $this->idarticulo; }
	public function setIdarticulo($idarticulo) { $this->idarticulo = $idarticulo; }
	public function getCantidad() { return $this->cantidad; }
	public function setCantidad($cantidad) { $this->cantidad = $cantidad; }

}

?>