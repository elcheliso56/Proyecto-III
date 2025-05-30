<?php  
function validar_paciente($data) {
    $errores = [];
    // Cedula: numérica, 7-8 dígitos
    if (empty($data['cedula']) || !preg_match('/^[0-9]{7,8}$/', $data['cedula'])) {
        $errores[] = 'Cédula inválida';
    }
    // Nombre: solo letras, 2-30 caracteres
    if (empty($data['nombre']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{2,30}$/u', $data['nombre'])) {
        $errores[] = 'Nombre inválido';
    }
    // Apellido: solo letras, 2-30 caracteres
    if (empty($data['apellido']) || !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{2,30}$/u', $data['apellido'])) {
        $errores[] = 'Apellido inválido';
    }
    // Fecha de nacimiento: formato YYYY-MM-DD
    if (empty($data['fecha_nacimiento']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_nacimiento'])) {
        $errores[] = 'Fecha de nacimiento inválida';
    }
    // Género: F, M, O
    if (empty($data['genero']) || !in_array($data['genero'], ['F','M','O'])) {
        $errores[] = 'Género inválido';
    }
    // Alergias: 2-20 caracteres
    if (empty($data['alergias']) || !preg_match('/^[A-Za-z\s,.-]{2,20}$/u', $data['alergias'])) {
        $errores[] = 'Alergias inválidas';
    }
    // Antecedentes: 2-20 caracteres
    if (empty($data['antecedentes']) || !preg_match('/^[A-Za-z\s,.-]{2,20}$/u', $data['antecedentes'])) {
        $errores[] = 'Antecedentes inválidos';
    }
    // Email: opcional, pero si existe debe ser válido
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Email inválido';
    }
    // Teléfono: 04120000000
    if (empty($data['telefono']) || !preg_match('/^0[0-9]{10}$/', $data['telefono'])) {
        $errores[] = 'Teléfono inválido';
    }
    // Dirección: 1-100 caracteres, sin comillas
    if (empty($data['direccion']) || !preg_match('/^[^"\\\']{1,100}$/u', $data['direccion'])) {
        $errores[] = 'Dirección inválida';
    }
    // Fecha de registro: formato YYYY-MM-DD
    if (empty($data['fecha_registro']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_registro'])) {
        $errores[] = 'Fecha de registro inválida';
    }
    if (empty($data['salario']) || !preg_match('/^[0-9]{7,8}$/', $data['salario'])) {
    $errores[] = 'Salario inválido';
    }
    return $errores;
}
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
	exit; // Termina la ejecución
}  
require_once("modelo/".$pagina.".php"); // Incluye el archivo del modelo
// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){	    
	if(!empty($_POST)){ // Comprueba si hay datos enviados por POST
		$o = new pacientes(); // Crea una instancia de la clase clientes
		$accion = $_POST['accion']; // Obtiene la acción a realizar
		// Acción para consultar datos
		if($accion=='consultar'){
            echo  json_encode($o->consultar()); // Devuelve los datos en formato JSON
		}
		// Acción para eliminar un paciente
		elseif($accion=='eliminar'){
			$o->set_cedula($_POST['cedula']); // Establece el número de documento
			echo  json_encode($o->eliminar()); // Elimina y devuelve el resultado en JSON
		}
		else{		  
			// Establece los datos del paciente
			$o->set_cedula($_POST['cedula']);
			$o->set_nombre($_POST['nombre']);
			$o->set_apellido($_POST['apellido']);
            $o->set_fecha_nacimiento($_POST['fecha_nacimiento']);
            $o->set_genero($_POST['genero']);
            $o->set_alergias($_POST['alergias']);
            $o->set_antecedentes($_POST['antecedentes']);
			$o->set_email($_POST['email']);
			$o->set_telefono($_POST['telefono']);
			$o->set_direccion($_POST['direccion']);
            $o->set_fecha_registro($_POST["fecha_registro"]); // Establece la fecha de registro
			
            $errores = validar_paciente($_POST);
			if (count($errores) > 0) {
				echo json_encode(['resultado'=>'error','mensaje'=>implode(', ', $errores)]);
				exit;
			}
			// Acción para incluir un nuevo paciente
			if($accion=='incluir'){
				echo  json_encode($o->incluir()); // Incluye y devuelve el resultado en JSON
			}
			// Acción para modificar un paciente existente
			elseif($accion=='modificar'){
				echo  json_encode($o->modificar()); // Modifica y devuelve el resultado en JSON
			}
		}
		exit; // Termina la ejecución después de procesar la acción
	}	  
	require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
	echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>
