<?php
	function GetDatos($name, $valorDefault) {
		$dato = $valorDefault;
		
		if(isset($_GET[$name]))
			$dato = $_GET[$name];
		else if(isset($_POST[$name]))
			$dato = $_POST[$name];
		
		return $dato;
	}

	$matricula = GetDatos("matricula", "0");
	$nombre = GetDatos("nombre", "Sin nombre");
	$fechNcmt = GetDatos("fechNcmt", "1999-1-1");
	$estados = GetDatos("estados", "Tamaulipas");
	$carrera = GetDatos("carrera", "Ingeniería en Tecnologías de la Información")
?>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <form action="" method="post" id="alumnoForm">
        Matricula:<br>
        <input type="text" name="matricula" <?php echo "value=" . $matricula; ?> ><br> 

	Nombre:<br>
        <textarea name="nombre"><?php echo $nombre; ?></textarea><br> 
	Fecha de nacimiento:<br>
        <input type="date" name="fechNcmt" <?php echo "value=" . $fechNcmt; ?>> <br> 
	Estado de nacimiento:<br>
        <select name="estados" form="alumnoForm">

		<option value="Todo México">Todo México</option>

		<option value="Aguascalientes">Aguascalientes</option>

		<option value="Baja California">Baja California</option>

		<option value="Baja California Sur">Baja California Sur</option>

		<option value="Campeche">Campeche</option>

		<option value="CDMX">CDMX</option>

		<option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>

		<option value="Colima">Colima</option>

		<option value="Chiapas">Chiapas</option>

		<option value="Chihuahua">Chihuahua</option>

		<option value="Durango">Durango</option>

		<option value="Guanajuato">Guanajuato</option>

		<option value="Guerrero">Guerrero</option>

		<option value="Hidalgo">Hidalgo</option>

		<option value="Jalisco">Jalisco</option>

		<option value="México">México</option>

		<option value="Michoacán de Ocampo">Michoacán de Ocampo</option>

		<option value="Morelos">Morelos</option>

		<option value="Nayarit">Nayarit</option>

		<option value="Nuevo León">Nuevo León</option>

		<option value="Oaxaca">Oaxaca</option>

		<option value="Puebla">Puebla</option>

		<option value="Querétaro">Querétaro</option>

		<option value="Quintana Roo">Quintana Roo</option>

		<option value="San Luis Potosí">San Luis Potosí</option>

		<option value="Sinaloa">Sinaloa</option>

		<option value="Sonora">Sonora</option>

		<option value="Tabasco">Tabasco</option>

		<option value="Tamaulipas">Tamaulipas</option>

		<option value="Tlaxcala">Tlaxcala</option>

		<option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>

		<option value="Yucatán">Yucatán</option>

		<option value="Zacatecas">Zacatecas</option>

	</select><br> Carrera:

        <br>
        <input type="radio" name="carrera" value="iti">Ingeniería en Tecnologías de la Información<br>
        <input type="radio" name="carrera" value="industrial">Ingeniería Industrial<br>
        <input type="radio" name="carrera" value="energia">Ingeniería en Energía<br>
        <input type="radio" name="carrera" value="electronica">Ingeniería en Electrónica<br> 
	Avance de estancias/estadias:<br>
        <input type="checkbox" name="estc1" value="1">Estancia 1<br>
        <input type="checkbox" name="estc2" value="1">Estancia 2<br>
        <input type="checkbox" name="estd1" value="1">Estadia 1<br><br>
        <input type="submit" value="Enviar"><br><br>
	<input type="text" value="<?php echo $matricula . " " . $nombre . " " . $fechNcmt . " " . $estados . " " . $carrera; ?>" name="variable">
    </form>

</body>

</html>