<?php
// Funcion de auto carga de un archivo
function autocargador($clase){
	$archivo = RAIZ."/objetos/".$clase.".php";
    if(!file_exists($archivo)){
        exit("Error en la carga del archivo ".$archivo);
    }
    else
        require_once($archivo);
}

spl_autoload_register('autocargador');
?>