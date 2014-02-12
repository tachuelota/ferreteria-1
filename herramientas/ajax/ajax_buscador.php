<?php

require_once "../../utilidades/configuracion.php";

if(isset($_POST['busquedaClientes'])) {
	
	$condiciones = array();
	foreach($_POST['busquedaClientes'] as $campo)
		$condiciones[] = array($campo["name"], "LIKE", "%{$campo['value']}%");
	$condiciones[] = array("activo", "=", "1");

	$datos = json_encode(Cliente::buscar(array("condiciones" => $condiciones)));
}

echo $datos;


?>