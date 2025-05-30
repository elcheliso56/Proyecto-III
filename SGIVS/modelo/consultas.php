<?php
require_once('modelo/datos.php');
class consultas extends datos
{
	// Propiedades del cliente	
	private $cedula;
	private $nombre;
	private $Apellido;
	private $telefono;
	private $tratamiento;
	private $fechaconsulta;
	private $doctor;



	// Métodos para establecer los valores de las propiedades	
	function set_cedula($valor)
	{
		$this->cedula = $valor;
	}
	function set_nombre($valor)
	{
		$this->nombre = $valor;
	}
	function set_Apellido($valor)
	{
		$this->Apellido = $valor;
	}
	function set_telefono($valor)
	{
		$this->telefono = $valor;
	}
	function set_tratamiento($valor)
	{
		$this->tratamiento = $valor;
	}
	function set_fechaconsulta($valor)
	{
		$this->fechaconsulta = $valor;
	}
	function set_doctor($valor)
	{
		$this->doctor = $valor;
	}

	// Métodos para obtener los valores de las propiedades		

	function get_cedula()
	{
		return $this->cedula;
	}
	function get_nombre()
	{
		return $this->nombre;
	}
	function get_Apellido()
	{
		return $this->Apellido;
	}
	function get_telefono()
	{
		return $this->telefono;
	}
	function get_tratamiento()
	{
		return $this->tratamiento;
	}
	function get_fechaconsulta()
	{
		return $this->fechaconsulta;
	}
	function get_doctor()
	{
		return $this->doctor;
	}


	// Método para incluir un nuevo cliente en la base de datos
	function incluir()
	{
		$r = array();
		// Verifica si el número de documento ya existe		
		if (!$this->existe1($this->cedula)) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				// Inserta el nuevo cliente			
				$co->query("Insert into consultas(cedula,nombre,Apellido,telefono,tratamiento,fechaconsulta,doctor)
					Values(
					'$this->cedula',
					'$this->nombre',
					'$this->Apellido',
					'$this->telefono',
                    '$this->tratamiento',
                    '$this->fechaconsulta',
                    '$this->doctor')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el numero de documento';
		}
		return $r;
	}

	// Método para modificar un cliente existente	
	function modificar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		// Verifica si el número de documento existe		
		if ($this->existe2($this->cedula)) {
			try {
				// Actualiza los datos del cliente				
				$co->query("UPDATE consultas SET 
					nombre = '$this->nombre',
					Apellido = '$this->Apellido',
					telefono = '$this->telefono',
					tratamiento = '$this->tratamiento',
					fechaconsulta = '$this->fechaconsulta',
					doctor = '$this->doctor'
					WHERE cedula = '$this->cedula' LIMIT 1
				");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro modificado con éxito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'numero de documento no registrado';
		}
		return $r;
	}

	// Método para eliminar un cliente	
	function eliminar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		// Verifica si el número de documento existe		
		if ($this->existe($this->cedula)) {
			try {
				// Elimina el cliente de la base de datos
				$co->query("DELETE FROM consultas WHERE cedula = '$this->cedula' LIMIT 1");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		} else {
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el numero de documento';
		}
		return $r;
	}

	// Método para consultar todos los consultas	
	function consultar()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select * from consultas  ORDER BY id DESC");
			if ($resultado) {
				$respuesta = '';
				$n = 1;
				// Recorre los resultados y genera la tabla HTML
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr class='text-center'>";
					$respuesta = $respuesta . "<td class='align-middle'>$n</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['cedula'] . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['nombre'] . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['Apellido'] . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['telefono'] . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['tratamiento'] . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['fechaconsulta'] . "</td>";
					$respuesta = $respuesta . "<td class='align-middle'>" . $r['doctor'] . "</td>";
					/* $respuesta = $respuesta . "<td class='align-middle'>";
				$respuesta = $respuesta .
						"<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar cliente'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta = $respuesta . "<button  type='button'
					class='btn-sm btn-danger w-50 small-width mt-1' 
					onclick='pone(this,1)'
					title='Eliminar cliente'
					><i class='bi bi-trash'></i></button><br/>";
					$respuesta = $respuesta . "</td>";*/
					$respuesta = $respuesta . "</tr>";
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  '';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
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

	function listadodoc()
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {

			$resultado = $co->query("Select * from empleados
			where
					cargo = 'Doctor'
					");

			if ($resultado) {

				$respuesta = '';
				foreach ($resultado as $r) {
					$respuesta = $respuesta . "<tr style='cursor:pointer' onclick='colocadoc(this);'>";


					$respuesta = $respuesta . "<td>";
					$respuesta = $respuesta . $r['nombre'] . ' ' . $r['apellido'];
					$respuesta = $respuesta . "</td>";

					$respuesta = $respuesta . "</tr>";
				}
				$r['resultado'] = 'modaldoc';
				$r['mensaje'] =  $respuesta;
			} else {
				$r['resultado'] = 'modaldoc';
				$r['mensaje'] =  '';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	// Método privado para verificar si un número de documento ya existe
	private function existe1($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("Select * from consultas where id ='$id'");
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if ($fila) {
				return true;
			} else {
				return false;;
			}
		} catch (Exception $e) {
			return false;
		}
	}
	private function existe($cedula)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("Select * from consultas where cedula='$cedula'");
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if ($fila) {
				return true;
			} else {
				return false;;
			}
		} catch (Exception $e) {
			return false;
		}
	}

	private function existe2($id)
	{
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("Select * from consultas where cedula ='$id'");
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if ($fila) {
				return true;
			} else {
				return false;;
			}
		} catch (Exception $e) {
			return false;
		}
	}
}
