<?php
require_once('modelo/datos.php');
class equipos extends datos{
    // Propiedades de la clase	
	private $codigo; 
	private $nombre;
	private $marca;
	private $modelo;
	private $cantidad;
	private $imagen;

    // Métodos para establecer valores de las propiedades	
	function set_codigo($valor){
		$this->codigo = $valor; 
	}
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	function set_marca($valor){
		$this->marca = $valor;
	}	
	function set_modelo($valor){
		$this->modelo = $valor;
	}	
	function set_cantidad($valor){
		$this->cantidad = $valor;
	}
	function set_imagen($valor){
		$this->imagen = $valor;
	}

	function get_codigo(){
		return $this->codigo;
	}	
	function get_nombre(){
		return $this->nombre;
	}
	function get_marca(){
		return $this->marca;
	}
	function get_modelo(){
		return $this->modelo;
	}	
	function get_cantidad(){
		return $this->cantidad;
	}
	function get_imagen(){
		return $this->imagen;
	}	

    // Método para incluir un nuevo equipo
	function incluir(){
		$r = array();
        // Verifica si el código ya existe		
		if(!$this->existe($this->codigo)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo equipo en la base de datos				
				$co->query("INSERT INTO equipos(codigo, nombre, marca, modelo, cantidad, imagen)
					VALUES(
						'$this->codigo',
						'$this->nombre',
						'$this->marca',
						'$this->modelo',
						'$this->cantidad',
						'$this->imagen'
					)");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el código del equipo';
		}
		return $r;
	}

    // Método para modificar un equipo existente
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el equipo existe
		if($this->existe($this->codigo)){
			try {
	            // Obtiene la imagen actual del equipo			
				$resultado = $co->query("SELECT imagen FROM equipos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];                
				// Actualiza los datos del equipo	

				$co->query("UPDATE equipos set 
					codigo = '$this->codigo',
					nombre = '$this->nombre',

					marca = '$this->marca',
					modelo = '$this->modelo',
					cantidad = '$this->cantidad',
					imagen = '$this->imagen'
					where
					codigo = '$this->codigo'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro actualizado con éxito!';                
				// Elimina la imagen anterior si es necesario

				if ($imagen_actual && $imagen_actual != 'otros/img/equipos/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Codigo no registrado';
		}
		return $r;
	}

    // Método para eliminar un equipo	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();        
		// Verifica si el equipo existe
		if($this->existe($this->codigo)){
			try {                
			// Obtiene la imagen del equipo a eliminar
				$resultado = $co->query("SELECT imagen FROM equipos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				// Elimina el equipoo de la base de datos

				$co->query("DELETE from equipos where codigo = '$this->codigo' ");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				// Elimina la imagen del equipo si existe
				if ($imagen && $imagen != 'otros/img/equipos/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este equipo porque tiene movimientos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el código del equipo';
		}
		return $r;
	}

    // Método para consultar equipos		
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
            // Realiza la consulta para obtener equipos y sus detalles


			$resultado = $co->query("Select * from equipos ORDER BY id DESC");
			if ($resultado->rowCount() > 0) {
				$respuesta = "";
				$productos_bajo_stock = array();
				$n = 1;
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){                    
				// Genera la respuesta en formato HTML
					$respuesta .= "<tr>";
					$respuesta .= "<td>$n</td>";
					$respuesta .= "<td>".$row['codigo']."</td>";
					$respuesta .= "<td>".$row['nombre']."</td>";
					$respuesta .= "<td>".$row['marca']."</td>";
					$respuesta .= "<td>".$row['modelo']."</td>";
					$respuesta .= "<td>".$row['cantidad'].
					($row['cantidad'] == 0 ? "<br><span class='badge bg-danger' title='Este equipo esta agotado'>No disponible</span>" : " ")."</td>";
					$respuesta .= "<td><a href='".$row['imagen']."' target='_blank'><img src='".$row['imagen']."' alt='Imagen del equipo' class='img'/></a></td>";	
					$respuesta .= "<td>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar equipo'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar equipo'><i class='bi bi-trash'></i></button><br/>";
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
					// Verifica si el stock es bajo
					if ($row['cantidad'] <= 0) {
						$productos_bajo_stock[] = array(
							'nombre' => $row['nombre'],
							'cantidad' => $row['cantidad']
						);
					}
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] = $respuesta;
				$r['productos_bajo_stock'] = $productos_bajo_stock;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] = '';
			}
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

    // Método privado para verificar si un equipo existe	
	private function existe($codigo){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from equipos where codigo='$codigo'");	
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
				return true;   
			}
			else{	
				return false;;
			}	
		}catch(Exception $e){
			return false;
		}
	}	
    // Método para obtener equipos que necesitan notificación		
	function obtenerEquiposNotificacion() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT codigo, nombre, cantidad FROM equipos WHERE cantidad = 0");
			$equipos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $equipos;
		} catch(Exception $e) {
			return array();
		}
	}
}
?>