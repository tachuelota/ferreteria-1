<?php
// Configuracion de php
// Variable raiz sirve para checar la ubicacion de controlPersonal
$dir = "/ferreteria";
define("RAIZ",$_SERVER['DOCUMENT_ROOT'].$dir);
define('PATH',"http://".$_SERVER['SERVER_NAME'].$dir);
date_default_timezone_set('America/Mexico_City');

// Autoload es para cargar las clases
require_once "autoload.php";
?>