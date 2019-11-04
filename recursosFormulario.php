<?php
	
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		echo "<fieldset><legend>Linea</legend>";
		$texto = DibujaElemento('linea', 'Hola mundo', null, null);
		echo $texto . "</fieldset><br>";

		echo "<fieldset><legend>Text</legend>";
		$texto = DibujaElemento('text', 'Hola mundo', null, null);
		echo $texto . "</fieldset><br>";

		echo "<fieldset><legend>Date</legend>";
		$texto = DibujaElemento('date', '1999-05-08', null, null);
		echo $texto . "</fieldset><br>";

		echo "<fieldset><legend>TextArea</legend>";
		$texto = DibujaElemento('textarea', 'Hola mundo', null, null);
		echo $texto . "</fieldset><br>";

		$estados = array('Veracruz', 'Tamaulipas', 'Jalisco');

		echo "<fieldset><legend>Select</legend>";
		$texto = DibujaElemento('select', 'Tamaulipas', $estados, null);
		echo $texto . "</fieldset><br>";

		echo "<fieldset><legend>Radio</legend>";
		$texto = DibujaElemento('radio', 'Tamaulipas', $estados, null);
		echo $texto . "</fieldset><br>";

		echo "<fieldset><legend>CheckBox</legend>";
		$valores = array('Tamaulipas', 'Jalisco');
		$texto = DibujaElemento('checkbox', '', $estados, $valores);
		echo $texto . "</fieldset><br>";

	?>
</body>
</html>