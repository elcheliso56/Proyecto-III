<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/" . $pagina . ".php")) {
	echo "Falta definir la clase " . $pagina; // Mensaje de error si no se encuentra el modelo
	exit;
}
require_once("modelo/" . $pagina . ".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if (is_file("vista/" . $pagina . ".php")) {
	if (!empty($_POST)) { // Comprueba si hay datos enviados por POST
		$o = new historial();   // Crea una nueva instancia de la clase historial
		$accion = $_POST['accion']; // Obtiene la acción a realizar

		// Acción para consultar datos
		if ($accion == 'consultar') {
			echo  json_encode($o->consultar());  // Devuelve los datos en formato JSON
		}
		// Acción para eliminar un registro
		elseif ($accion == 'eliminar') {
			$o->set_nombre($_POST['nombre']); // Establece el nombre del registro a eliminar
			echo  json_encode($o->eliminar()); // Devuelve el resultado de la eliminación en JSON
		} else {
			// Acciones para incluir o modificar un registro
			if ($accion == 'incluir' || $accion == 'modificar') {
				$o->set_nombre($_POST['nombre']);
				$o->set_Apellido($_POST['Apellido']);
				$o->set_Ocupacion($_POST['Ocupacion']); // Establece la fecha del registro
				$o->set_Sexo($_POST['Sexo']); // Establece la descripción del registro
				$o->set_PersonaContacto($_POST['PersonaContacto']); // Establece la persona de contacto del registro
				$o->set_telefono($_POST['telefono']); // Establece el teléfono del registro
				$o->set_Edad($_POST['Edad']); // Establece la edad del registro
				$o->set_correo($_POST['correo']); // Establece el correo electrónico del registro
				$o->set_diagnostico($_POST['diagnostico']); // Establece el diagnóstico del registro
				$o->set_tratamiento($_POST['tratamiento']); // Establece el tratamiento del registro
				$o->set_medicamentos($_POST['medicamentos']); // Establece los medicamentos del registro
				$o->set_dientesafectados($_POST['dientesafectados']); // Establece los dientes afectados del registro
				$o->set_antecedentes($_POST['antecedentes']); // Establece los antecedentes del registro
				$o->set_fechaconsulta($_POST['fechaconsulta']); // Establece la fecha de consulta del registro
				$o->set_proximacita($_POST['proximacita']); // Establece la próxima cita del registro
				$o->set_observaciones($_POST['observaciones']); // Establece las observaciones del registro
				// Establece el Apellido del registro
				/*
				// Manejo de la imagen subida
				if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
					$imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
					$imagen_ruta = 'otros/img/historial/' . $imagen_nombre; // Define la ruta de la imagen
					move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
					$o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el objeto
				} else {
					$o->set_imagen('otros/img/historial/default.png'); // Establece una imagen por defecto si no se sube ninguna
				}*/

				// Acción para incluir un nuevo registro

				// Establece el nombre del nuevo registro
				echo json_encode($o->incluir()); // Devuelve el resultado de la inclusión en JSON
			}
			// Acción para modificar un registro existente
			elseif ($accion == 'modificar') {
				$o->set_nombre($_POST['nombre']);
				$o->set_Apellido($_POST['Apellido']);
				$o->set_Ocupacion($_POST['Ocupacion']); // Establece la fecha del registro
				$o->set_Sexo($_POST['Sexo']); // Establece la descripción del registro
				$o->set_PersonaContacto($_POST['PersonaContacto']); // Establece la persona de contacto del registro
				$o->set_telefono($_POST['telefono']); // Establece el teléfono del registro
				$o->set_Edad($_POST['Edad']); // Establece la edad del registro
				$o->set_correo($_POST['correo']); // Establece el correo electrónico del registro
				$o->set_diagnostico($_POST['diagnostico']); // Establece el diagnóstico del registro
				$o->set_tratamiento($_POST['tratamiento']); // Establece el tratamiento del registro
				$o->set_medicamentos($_POST['medicamentos']); // Establece los medicamentos del registro
				$o->set_dientesafectados($_POST['dientesafectados']); // Establece los dientes afectados del registro
				$o->set_antecedentes($_POST['antecedentes']); // Establece los antecedentes del registro
				$o->set_fechaconsulta($_POST['fechaconsulta']); // Establece la fecha de consulta del registro
				$o->set_proximacita($_POST['proximacita']); // Establece la próxima cita del registro
				$o->set_observaciones($_POST['observaciones']); // Establece el nombre del registro a modificar
				echo json_encode($o->modificar()); // Devuelve el resultado de la modificación en JSON
			}
		}
		exit; // Termina la ejecución del script
	}
	require_once("vista/" . $pagina . ".php"); // Incluye el archivo de vista
} else {
	echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>