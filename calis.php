<?php

class Padre {
	
	function funcionPadre() {
		
	}

	function hello(Padre $hijo) {
		var_dump($hijo);
	}

}

class Hijo extends Padre {

}

class Otro {

}

$hijo = new Otro();

$h = new Hijo();
$h->hello($hijo);


?>