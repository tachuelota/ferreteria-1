<?php

class Elemento {

	protected $id;
	protected $activo;
	protected static $opciones =
		array(
			"campos" => "*",
			"joins" => array(),
			"condiciones" => array(),
			"limite" => null,
			"orden" => null,
			"agrupaciones" => array()
		);

	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
	public function getActivo(){ return $this->activo; }
	public function setActivo($activo){ $this->activo = $activo; }
	public function getAtributos() { return get_object_vars($this); }

	public function insertar() {
		$atributos = $this->getAtributos();
		array_pop($atributos);
		array_pop($atributos);
		$consulta = "INSERT INTO ".static::$tabla." (".implode(",", array_keys($atributos)).")
		 VALUES (". implode(",", array_map(function() { return "?"; }, $atributos)) .")";
		$conexion = Conexion::conectar();
		$sentencia = $conexion->prepare($consulta);
		$sentencia->execute(array_values($atributos));
		$insertado = $conexion->lastInsertId();
		$conexion = null;
		return $insertado;
	}

	public function actualizar() {
		$atributos = $this->getAtributos();
		unset($atributos["id"]);
        $consulta = "UPDATE ".static::$tabla."
        SET ".implode(",", array_map(function($atributo) { return $atributo."=?"; }, array_keys($atributos)))." WHERE id = ?";
        array_push($atributos, $this->id);
        $conexion = Conexion::conectar();
        $sentencia = $conexion->prepare($consulta);
		$sentencia->execute(array_values($atributos));
		$conexion = null;
		return $sentencia->rowCount();
    }

	public static function eliminar($condiciones = array()) {

		$condiciones = $condiciones && count($condiciones) === count($condiciones, COUNT_RECURSIVE) ? array($condiciones) : $condiciones;

		$consulta = "DELETE FROM ".static::$tabla;
		$consulta .= $condiciones ? (" WHERE " . implode(" AND ", 
			array_map(function($condicion) {
				return "$condicion[0] $condicion[1] ?";	}, $condiciones))) : "";

		$conexion = Conexion::conectar();
		$sentencia = $conexion->prepare($consulta);
		
		foreach($condiciones as $i => $condicion)
			$sentencia->bindParam($i+1, $condicion[2]);

		$sentencia->execute();
		$conexion = null;
		return $sentencia->rowCount();
	}

    public static function buscar($opciones = null, $fetch_class = null) {

		$opciones = $opciones ? $opciones + self::$opciones : self::$opciones;

		$joins = array();
		foreach($opciones["joins"] as $tabla => $join)
			$joins[] = is_array($join) ? "INNER JOIN $tabla" . (empty($join) ? "" : " ON $join[0] $join[1] $join[2]") : "NATURAL JOIN $join";
		$joins = $joins ? implode(" ", $joins) : "";

		$opciones["condiciones"] = $opciones["condiciones"] && count($opciones["condiciones"]) === count($opciones["condiciones"], COUNT_RECURSIVE) ? array($opciones["condiciones"]) : $opciones["condiciones"];
		$condiciones = array_map(function($condicion) {
			return "$condicion[0] $condicion[1] ?";
		}, $opciones["condiciones"]);
		$condiciones = $condiciones ? implode(" AND ", $condiciones) : "";

		$agrupaciones = "";
		foreach($opciones["agrupaciones"] as $campo => $condicion)
			$agrupaciones = is_array($condicion) ? "GROUP BY $campo HAVING $condicion[0] $condicion[1] $condicion[2]" : " GROUP BY $condicion";

		$consulta = "SELECT {$opciones['campos']} FROM ".static::$tabla;
		$consulta .= $joins ? " $joins" : "";
		$consulta .= $condiciones ? " WHERE $condiciones" : "";
		$consulta .= $agrupaciones;
		$consulta .= $opciones["orden"] ? " ORDER BY {$opciones['orden'][0]} {$opciones['orden'][1]}" : "";
		$consulta .= $opciones["limite"] ? " LIMIT ?,?" : "";
		$conexion = Conexion::conectar();

		$sentencia = $conexion->prepare($consulta);

		foreach($opciones["condiciones"] as $i => $condicion)
			$sentencia->bindParam($i+1, $condicion[2]);

		$i = count($opciones["condiciones"]);
		if($opciones["limite"]) {
			$sentencia->bindParam($i+1, $opciones["limite"][0], PDO::PARAM_INT);
			$sentencia->bindParam($i+2, $opciones["limite"][1], PDO::PARAM_INT);
		}

		$sentencia->execute();
		$conexion = null;

		return
		$fetch_class ?
		$sentencia->fetchAll(PDO::FETCH_CLASS, $fetch_class) :
		$sentencia->fetchAll(PDO::FETCH_ASSOC);

	}

}

?>