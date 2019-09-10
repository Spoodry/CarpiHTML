<?php
	//$nombre = $_GET['nombre'];
	$matricula = $_GET['matricula'];
	$fechNacim = $_GET['fechNcmt'];
	$estado = $_GET['estados'];
	$carrera = $_GET['carrera'];
	echo "Tu nombre es " . GetDatos("nombre", "Sin nombre") . ". Naciste el " . $fechNacim . ". En el estado de " . $estado . ". ";
	
	switch($carrera) {
		case "iti":
			$carrera = utf8_encode("Ingeniería en Tecnologías de la Información");
			break;
		case "industrial":
			$carrera = utf8_encode("Ingeniería Industrial");
			break;
		case "energia":
			$carrera = utf8_encode("Ingeniería en Energía");
			break;
		case "electronica":
			$carrera = utf8_encode("Ingeniería en Electrónica");
			break;
	}

	echo "Eres de la carrera de " . $carrera . ". ";

	if(isset($_GET['estc1']))
		echo "Has realizado la Estancia 1. ";
	if(isset($_GET['estc2']))
		echo "Has realizado la Estancia 2. ";
	if(isset($_GET['estd1']))
		echo utf8_encode("Has realizado Estadías. ");

	function GetDatos($name, $valorDefault) {
		$dato = $valorDefault;
		
		if(isset($_GET[$name]))
			$dato = $_GET[$name];
		else if(isset($_POST[$name]))
			$dato = $_POST[$name];
		
		return $dato;
	}

?>