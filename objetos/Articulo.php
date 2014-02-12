<?php

class Articulo extends Elemento {

	protected $nombre;
	protected $idfamilia;
	protected $unidad;
	protected $existentes;
	protected $codigo;
	protected $precio_distribuidor;
	protected $precio_mayoreo;
	protected $precio_publico;
	protected static $tabla = 'articulo';

	public function getNombre() { return $this->nombre; }
	public function setNombre($nombre) { $this->nombre = $nombre; }
	public function getIdfamilia() { return $this->idfamilia; }
	public function setIdfamilia($idfamilia) { $this->idfamilia = $idfamilia; }
	public function getUnidad() { return $this->unidad; }
	public function setUnidad($unidad) { $this->unidad = $unidad; }
	public function getExistentes() { return $this->existentes; }
	public function setExistentes($existentes) { $this->existentes = $existentes; }
	public function getCodigo() { return $this->codigo; }
	public function setCodigo($codigo) { $this->codigo = $codigo; }
	public function getPrecioDistribuidor() { return $this->precio_distribuidor; }
	public function setPrecioDistribuidor($precio_distribuidor) { $this->precio_distribuidor = $precio_distribuidor; }
	public function getPrecioMayoreo() { return $this->precio_mayoreo; }
	public function setPrecioMayoreo($precio_mayoreo) { $this->precio_mayoreo = $precio_mayoreo; }
	public function getPrecioPublico() { return $this->precio_publico; }
	public function setPrecioPublico($precio_publico) { $this->precio_publico = $precio_publico; }

	public static function getRelacion($compras = null) {

		$compras = $compras !== null ? $compras : Compra::buscar();

		foreach($compras as &$compra)
        	$compra["articulos"] = CompraArticulo::buscar(array(
            	"campos" => "articulo.*, compra_articulo.cantidad",
            	"joins" => array(
            		"articulo" => array("compra_articulo.idarticulo", "=", "articulo.id")),
            	"condiciones" => array("compra_articulo.idcompra", "=", $compra["id"])
            	));

        return $compras;

    }

}

?>