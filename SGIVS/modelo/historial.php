<?php
require_once('modelo/datos.php');

class historial extends datos
{
	private $id;	
	private $nombre;
	private $Apellido;
	private $fecha_nacimiento;
	private $genero;
	private $telefono;
	private $observaciones;

	// Método para establecer el ID
	function set_id($valor){
		$this->id = $valor;
	}
	function get_id(){
		return $this->id;
	}

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
	function set_genero($valor)
	{
		$this->genero = $valor;
	}
	function set_PersonaContacto($valor)
	{
		$this->PersonaContacto = $valor;
	}
	function set_telefono($valor)
	{
		$this->telefono = $valor;
	}
	function set_fecha_nacimiento($valor)
	{
		$this->fecha_nacimiento = $valor;
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
	function get_genero()
	{
		return $this->genero;
	}
	function get_PersonaContacto()
	{
		return $this->PersonaContacto;
	}
	function get_telefono()
	{
		return $this->telefono;
	}
	function get_fecha_nacimiento()
	{
		return $this->fecha_nacimiento;
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
		if (!$this->existe($this->tratamiento)) { // Verifica si la ubicación ya existe
			$co = $this->conecta(); // Conecta a la base de datos
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				// Inserta la nueva ubicación en la base de datos
				$co->query("Insert into historial(nombre, Apellido, ocupacion, genero, personacontacto, telefono, fecha_nacimiento, correo, diagnostico, tratamiento, medicamentos, dientesafectados, antecedentes, fechaconsulta, proximacita, observaciones)
					Values(
					'$this->nombre',
					'$this->Apellido',
					'$this->Ocupacion',
					'$this->genero',
					'$this->PersonaContacto',
					'$this->telefono',
					'$this->fecha_nacimiento',
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
			 $co = null; // Cierra la conexión aquí
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
		try {
			// Actualiza la ubicación en la base de datos usando el ID
			$stmt = $co->prepare("UPDATE historial SET 
				nombre = ?,
				Apellido = ?,
				ocupacion = ?,
				genero = ?,
				personacontacto = ?,
				telefono = ?,
				fecha_nacimiento = ?,
				correo = ?,
				diagnostico = ?,
				tratamiento = ?,
				medicamentos = ?,
				dientesafectados = ?,
				antecedentes = ?,
				fechaconsulta = ?,
				proximacita = ?,
				observaciones = ?
				WHERE id = ?");
			
			if($stmt->execute([
				$this->nombre,
				$this->Apellido,
				$this->Ocupacion,
				$this->genero,
				$this->PersonaContacto,
				$this->telefono,
				$this->fecha_nacimiento,
				$this->correo,
				$this->diagnostico,
				$this->tratamiento,
				$this->medicamentos,
				$this->dientesafectados,
				$this->antecedentes,
				$this->fechaconsulta,
				$this->proximacita,
				$this->observaciones,
				$this->id
			])) {
				if($stmt->rowCount() > 0) {
					$r['resultado'] = 'modificar';
					$r['mensaje'] = '¡Registro actualizado con éxito!';
				} else {
					$r['resultado'] = 'error';
					$r['mensaje'] = 'No se encontró el registro a modificar';
				}
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] = 'Error al intentar modificar el registro';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		$co = null;
		return $r;
	}

	// Método para eliminar una ubicación
	function eliminar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			// Elimina el registro usando el ID
			$stmt = $co->prepare("DELETE FROM historial WHERE id = ? LIMIT 1");
			if($stmt->execute([$this->id])) {
				if($stmt->rowCount() > 0) {
					$r['resultado'] = 'eliminar';
					$r['mensaje'] = '¡Registro eliminado con éxito!';
				} else {
					$r['resultado'] = 'error';
					$r['mensaje'] = 'No se encontró el registro a eliminar';
				}
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] = 'Error al intentar eliminar el registro';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			if ($e->getCode() == 23000) {
				$r['mensaje'] = 'No se puede eliminar el registro porque está siendo utilizado en otras tablas';
			} else {
				$r['mensaje'] = $e->getMessage();
			}
		}
		$co = null;
		return $r;
	}

	// Método para consultar todas las historial
	function consultar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("Select * from historial ORDER BY id");
			$historial = [];
			foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
				// Calcular edad y clasificación
				$historial[] = $row;
			}
			$r['resultado'] = 'consultar';
			$r['mensaje'] = $historial;
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		 $co = null; // Cierra la conexión aquí
		return $r; // Retorna el resultado
	}


	
	// Método privado para verificar si existe un registro
	private function existe($nombre)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$encontrado = false;
		try {
			$resultado = $co->query("SELECT * FROM historial WHERE nombre = '$nombre'");
			if($resultado->rowCount() > 0){
				$encontrado = true;
			}
		} catch (Exception $e) {
			$encontrado = false;
		}
		return $encontrado;
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

		// Validar genero (requerido, solo "Masculino" o "Femenino")
		if (empty($this->genero) || !in_array($this->genero, ['Masculino', 'Femenino'])) {
			$errores['genero'] = 'El genero es requerido y debe ser Masculino o Femenino.';
		}

		// Validar PersonaContacto (opcional, solo letras y espacios)
		if (!empty($this->PersonaContacto) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $this->PersonaContacto)) {
			$errores['PersonaContacto'] = 'La persona de contacto solo debe contener letras.';
		}

		// Validar teléfono (requerido, solo números, mínimo 7 dígitos)
		if (empty($this->telefono) || !preg_match('/^\d{7,15}$/', $this->telefono)) {
			$errores['telefono'] = 'El teléfono es requerido y debe contener solo números (7 a 15 dígitos).';
		}

		// Validar fecha_nacimiento (requerido, número entre 0 y 120)
		if (!is_numeric($this->fecha_nacimiento) || $this->fecha_nacimiento < 0 || $this->fecha_nacimiento > 120) {
			$errores['fecha_nacimiento'] = 'La fecha_nacimiento debe ser un número entre 0 y 120.';
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
		 $co = null; // Cierra la conexión aquí
		return $r;
	}

}
	
?>