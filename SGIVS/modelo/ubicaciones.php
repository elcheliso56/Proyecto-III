<?php
require_once('modelo/datos.php');

class historial extends datos
{
	private $nombre;
	private $Apellido;
	private $Ocupacion;
	private $Sexo;
	private $PersonaContacto;
	private $telefono;
	private $Edad;
	private $correo;
	private $diagnostico;
	private $tratamiento;
	private $medicamentos;
	private $dientesafectados;
	private $antecedentes;
	private $fechaconsulta;
	private $proximacita;
	private $observaciones;


	// Método para establecer el nombre
	function set_nombre($valor)
	{
		$this->nombre = $valor;
	}
	function set_Apellido($valor)
	{
		$this->Apellido = $valor;
	}
	function set_Ocupacion($valor)
	{
		$this->Ocupacion = $valor;
	}
	function set_Sexo($valor)
	{
		$this->Sexo = $valor;
	}
	function set_PersonaContacto($valor)
	{
		$this->PersonaContacto = $valor;
	}
	function set_telefono($valor)
	{
		$this->telefono = $valor;
	}
	function set_Edad($valor)
	{
		$this->Edad = $valor;
	}
	function set_correo($valor)
	{
		$this->correo = $valor;
	}
	function set_diagnostico($valor)
	{
		$this->diagnostico = $valor;
	}
	function set_tratamiento($valor)
	{
		$this->tratamiento = $valor;
	}
	function set_medicamentos($valor)
	{
		$this->medicamentos = $valor;
	}
	function set_dientesafectados($valor)
	{
		$this->dientesafectados = $valor;
	}
	function set_antecedentes($valor)
	{
		$this->antecedentes = $valor;
	}
	function set_fechaconsulta($valor)
	{
		$this->fechaconsulta = $valor;
	}
	function set_proximacita($valor)
	{
		$this->proximacita = $valor;
	}
	function set_observaciones($valor)
	{
		$this->observaciones = $valor;
	}
	// Método para obtener el Apellido

	// Método para obtener el nombre
	function get_nombre()
	{
		return $this->nombre;
	}
	function get_Apellido()
	{
		return $this->Apellido;
	}
	function get_Ocupacion()
	{
		return $this->Ocupacion;
	}
	function get_Sexo()
	{
		return $this->Sexo;
	}
	function get_PersonaContacto()
	{
		return $this->PersonaContacto;
	}
	function get_telefono()
	{
		return $this->telefono;
	}
	function get_Edad()
	{
		return $this->Edad;
	}
	function get_correo()
	{
		return $this->correo;
	}
	function get_diagnostico()
	{
		return $this->diagnostico;
	}
	function get_tratamiento()
	{
		return $this->tratamiento;
	}
	function get_medicamentos()
	{
		return $this->medicamentos;
	}
	function get_dientesafectados()
	{
		return $this->dientesafectados;
	}
	function get_antecedentes()
	{
		return $this->antecedentes;
	}
	function get_fechaconsulta()
	{
		return $this->fechaconsulta;
	}
	function get_proximacita()
	{
		return $this->proximacita;
	}
	function get_observaciones()
	{
		return $this->observaciones;
	}


	// Método para incluir una nueva ubicación
	function incluir()
	{
		$r = array();
		if (!$this->existe($this->nombre)) { // Verifica si la ubicación ya existe
			$co = $this->conecta(); // Conecta a la base de datos
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				// Inserta la nueva ubicación en la base de datos
				$co->query("Insert into historial(nombre, Apellido, ocupacion, sexo, personacontacto, telefono, edad, correo, diagnostico, tratamiento, medicamentos, dientesafectados, antecedentes, fechaconsulta, proximacita, observaciones)
					Values(
					'$this->nombre',
					'$this->Apellido',
					'$this->Ocupacion',
					'$this->Sexo',
					'$this->PersonaContacto',
					'$this->telefono',
					'$this->Edad',
					'$this->correo',
					'$this->diagnostico',
					'$this->tratamiento',
					'$this->medicamentos',
					'$this->dientesafectados',
					'$this->antecedentes',
					'$this->fechaconsulta',
					'$this->proximacita',
					'$this->observaciones')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] = '¡Registro guardado con éxito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage(); // Captura errores
			}
		} else {
			$r['resultado'] = 'incluir';
			$r['mensaje'] = 'Ya existe el nombre de documento'; // Mensaje si ya existe
		}
		return $r; // Retorna el resultado
	}

	// Método para modificar una ubicación existente
	function modificar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if ($this->existe($this->nombre)) { // Verifica si la ubicación existe
			try {
				// Obtiene la imagen actual de la ubicación
				$resultado = $co->query("SELECT imagen FROM historial WHERE nombre = '$this->nombre'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				//$imagen_actual = $fila['imagen'];
				// Actualiza la ubicación en la base de datos
				$co->query("UPDATE historial SET 
					Apellido = '$this->Apellido',
					ocupacion = '$this->Ocupacion',
					sexo = '$this->Sexo',
					personacontacto = '$this->PersonaContacto',
					telefono = '$this->telefono',
					edad = '$this->Edad',
					correo = '$this->correo',
					diagnostico = '$this->diagnostico',
					tratamiento = '$this->tratamiento',
					medicamentos = '$this->medicamentos',
					dientesafectados = '$this->dientesafectados',
					antecedentes = '$this->antecedentes',
					fechaconsulta = '$this->fechaconsulta',
					proximacita = '$this->proximacita',
					observaciones = '$this->observaciones'
					WHERE
					nombre = '$this->nombre'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] = '¡Registro actualizado con éxito!';
				// Elimina la imagen anterior si es necesario
			
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage(); // Captura errores
			}
		} else {
			$r['resultado'] = 'modificar';
			$r['mensaje'] = 'nombre de documento no registrado'; // Mensaje si no existe
		}
		return $r; // Retorna el resultado
	}

	// Método para eliminar una ubicación
	function eliminar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if ($this->existe($this->nombre)) { // Verifica si la ubicación existe
			try {
				// Obtiene la imagen de la ubicación a eliminar
				$resultado = $co->query("SELECT nombre FROM historial WHERE nombre = '$this->nombre'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['nombre'];
				// Elimina la ubicación de la base de datos
				$co->query("DELETE FROM historial WHERE nombre = '$this->nombre'");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] = '¡Registro eliminado con éxito!';
				// Elimina la imagen si es necesario
				/*if ($imagen && $imagen != 'otros/img/historial/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}*/

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar el registro porque está relacionado con otros datos.';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}

		} else {
			$r['resultado'] = 'eliminar';
			$r['mensaje'] = 'No existe el nombre de documento'; // Mensaje si no existe
		}
		return $r; // Retorna el resultado
	}

	// Método para consultar todas las historial
	function consultar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select * from historial  ORDER BY id DESC");
			if ($resultado) {
				$respuesta = '';
				$n = 1; // Contador para las filas
				foreach ($resultado as $r) {
					$respuesta .= "<tr class='text-center'>"; // Crea una fila de tabla
					$respuesta .= "<td class='align-middle'>$n</td>"; // Número de fila
					$respuesta .= "<td class='align-middle'>" . $r['nombre'] . "</td>"; // Nombre
					$respuesta .= "<td class='align-middle'>" . $r['Apellido'] . "</td>"; // Apellido
					$respuesta .= "<td class='align-middle'>" . $r['telefono'] . "</td>"; // Teléfono
					$respuesta .= "<td class='align-middle'>" . $r['correo'] . "</td>"; // Correo
					
					//$respuesta .= "<td class='align-middle'><a href='" . $r['imagen'] . "' target='_blank'><img src='" . $r['imagen'] . "' alt='Imagen de la ubicación' class='img'/></a></td>"; // Imagen
					$respuesta .= "<td class='align-middle'>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar ubicación'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar ubicación'><i class='bi bi-trash'></i></button>";
					
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
					$n++; // Incrementa el contador
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] = $respuesta; // Retorna la tabla de resultados
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] = ''; // Sin resultados
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage(); // Captura errores
		}
		return $r; // Retorna el resultado
	}


	
	// Método privado para verificar si una ubicación existe
	private function existe($nombre)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("Select * from historial where nombre='$nombre'");
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if ($fila) {
				return true;
			} else {
				return false;
				;
			}
		} catch (Exception $e) {
			return false;
		}


	}
	
		// Validaciones para cada campo según su tipo
		public function validarCampos()
		{
			$errores = [];

			// Validar nombre (solo letras y espacios, requerido)
			if (empty($this->nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->nombre)) {
				$errores['nombre'] = 'El nombre es requerido y solo debe contener letras.';
			}

			// Validar Apellido (solo letras y espacios, requerido)
			if (empty($this->Apellido) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->Apellido)) {
				$errores['Apellido'] = 'El apellido es requerido y solo debe contener letras.';
			}

			// Validar Ocupacion (opcional, solo letras y espacios)
			if (!empty($this->Ocupacion) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->Ocupacion)) {
				$errores['Ocupacion'] = 'La ocupación solo debe contener letras.';
			}

			// Validar Sexo (requerido, solo "Masculino" o "Femenino")
			if (empty($this->Sexo) || !in_array($this->Sexo, ['Masculino', 'Femenino'])) {
				$errores['Sexo'] = 'El sexo es requerido y debe ser Masculino o Femenino.';
			}

			// Validar PersonaContacto (opcional, solo letras y espacios)
			if (!empty($this->PersonaContacto) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->PersonaContacto)) {
				$errores['PersonaContacto'] = 'La persona de contacto solo debe contener letras.';
			}

			// Validar teléfono (requerido, solo números, mínimo 7 dígitos)
			if (empty($this->telefono) || !preg_match('/^\d{7,15}$/', $this->telefono)) {
				$errores['telefono'] = 'El teléfono es requerido y debe contener solo números (7 a 15 dígitos).';
			}

			// Validar Edad (requerido, número entre 0 y 120)
			if (!is_numeric($this->Edad) || $this->Edad < 0 || $this->Edad > 120) {
				$errores['Edad'] = 'La edad debe ser un número entre 0 y 120.';
			}

			// Validar correo (opcional, formato de email)
			if (!empty($this->correo) && !filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
				$errores['correo'] = 'El correo electrónico no es válido.';
			}

			// Validar diagnostico (opcional, texto)
			// No se aplica validación estricta

			// Validar tratamiento (opcional, texto)
			// No se aplica validación estricta

			// Validar medicamentos (opcional, texto)
			// No se aplica validación estricta

			// Validar dientesafectados (opcional, texto)
			// No se aplica validación estricta

			// Validar antecedentes (opcional, texto)
			// No se aplica validación estricta

			// Validar fechaconsulta (requerido, formato fecha)
			if (empty($this->fechaconsulta) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->fechaconsulta)) {
				$errores['fechaconsulta'] = 'La fecha de consulta es requerida y debe tener el formato YYYY-MM-DD.';
			}

			// Validar proximacita (opcional, formato fecha)
			if (!empty($this->proximacita) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->proximacita)) {
				$errores['proximacita'] = 'La próxima cita debe tener el formato YYYY-MM-DD.';
			}

			// Validar observaciones (opcional, texto)
			// No se aplica validación estricta

			return $errores;
		}
		
	function listadopaciente()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {

			$resultado = $co->query("Select * from pacientes");

			if ($resultado) {

				$respuesta = '';
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocapa(this);'>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r['cedula'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r['nombre'];
					$respuesta = $respuesta . "</td>";

					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r['apellido'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r['telefono'];
					$respuesta = $respuesta . "</td>";
					$respuesta = $respuesta . "</tr>";
				}
				$r['resultado'] = 'modalpaciente';
				$r['mensaje'] =  $respuesta;
			} else {
				$r['resultado'] = 'modalpaciente';
				$r['mensaje'] =  '';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

}
	
?>