<?php

class Proveedor extends Elemento {

	protected $razon_social;
	protected static $tabla = 'proveedor';

	public function getRazonSocial() { return $this->razon_social; }
	public function setRazonSocial($razon_social) { $this->razon_social = $razon_social; }

	public static function getRelacion($articulos = null) {

		$articulos = $articulos !== null ? $articulos : Articulo::buscar(array(
			"condiciones" => array("articulo.activo", "=", 1),
		));

		foreach($articulos as &$articulo)
        	$articulo["proveedores"] = CodigoProveedor::buscar(array(
            	"campos" => "codigo_proveedor.idproveedor, codigo_proveedor.codigo_proveedor",
            	"condiciones" => array("codigo_proveedor.idarticulo", "=", $articulo["id"])
            	));

        return $articulos;

    }

}

?>