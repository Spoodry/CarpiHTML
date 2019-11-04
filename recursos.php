<?php
	function DibujaElemento($name, $tipoElemento, $valorDefault, $arreglo, $valores) {
		$codigoHtml = "";
		switch ($tipoElemento) {
			case 'linea':
				$codigoHtml = "<hr>" . $valorDefault . "<hr>";
				break;
			
			case 'text':
				$codigoHtml = "<input type='text' value='" . $valorDefault . "'> <br>";
				break;

			case 'date':
				$codigoHtml = "<input type='date' value='" . $valorDefault . "'> <br>";
				break;

			case 'textarea':
				$codigoHtml = "<textarea>" . $valorDefault . "</textarea> <br>";
				break;

			case 'select':
				$codigoHtml = "<select>";

				foreach ($arreglo as $estado) {
					$filtro = "";
					if($valorDefault == $estado)
						$filtro = " selected";
					$codigoHtml .= "<option value='" . $estado . "'" . $filtro . ">" . $estado . "</option>";
				}

				$codigoHtml .= "</select><br>";
				break;

			case 'radio':

				for($i = 0; $i < count($arreglo); $i++) {
					$filtro = "";
					if($arreglo[$i] == $valorDefault)
						$filtro = " checked";

					$codigoHtml .= "<input type='radio' name='radioxd' value='" . $arreglo[$i] . "'" . $filtro . ">" . $arreglo[$i] . "<br>";
				}

				break;

			case 'checkbox':

				for($i = 0; $i < count($arreglo); $i++) {
					$filtro = "";
					for($k = 0; $k < count($valores); $k++) {
						if($arreglo[$i] == $valores[$k])
							$filtro = "checked";	
					}
					
					$codigoHtml .= "<input type='checkbox' name='estado" . ($i + 1) . "' value='" . $arreglo[$i] . "'" . $filtro . ">" . $arreglo[$i] . "<br>";
				}

				break;

			default:

				break;
		}
		return $codigoHtml;
	}

	function GetDatos($name, $valorDefault) {
		$dato = $valorDefault;
		
		if(isset($_GET[$name]))
			$dato = $_GET[$name];
		else if(isset($_POST[$name]))
			$dato = $_POST[$name];
		
		return $dato;
	}

?>