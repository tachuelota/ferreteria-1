<?php

if(isset($_POST['generador'])) {

	$clase = $_POST['clase'];
	$atributos = explode("\r\n", $_POST['atributos']);

	$str = "<?php\n\n";
	$str .= "class $clase extends Elemento {\n\n";
	foreach ($atributos as $atributo)
		$str .= "\tprotected \$$atributo;\n";
	$str .= "\tprotected static \$tabla = '';\n";
	$str .= "\n";
	foreach ($atributos as $atributo) {
		$palabras = split("_", $atributo);
		$nombreFuncion = implode("", array_map("ucfirst", $palabras));
		$str .= "\tpublic function get$nombreFuncion() { return \$this->$atributo; }\n";
		$str .= "\tpublic function set$nombreFuncion(\$$atributo) { \$this->$atributo = \$$atributo; }\n";
	}
	
	$str .= "\n}";
	$str .= "\n\n?>";
	$fp = fopen($clase.'.php', 'w+');
	fwrite($fp, $str);
	fclose($fp);
	chmod($clase.'.php', 0777);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Generador</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
	<form method="post" action="">
		<input type="text" name="clase"/>
		<textarea name="atributos"></textarea>
		<input type="submit" name="generador" value"Crear"/>
	</form>
</body>
</html>