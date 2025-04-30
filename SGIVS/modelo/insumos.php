<?php
require_once('modelo/datos.php');
class insumos extends datos{
    // Propiedades de la clase	
	private $codigo; 
	private $nombre;
	private $marca;
	private $stock_total;
	private $stock_minimo;	
	private $precio;
	private $imagen;
	private $presentacion_id;

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
	function set_stock_total($valor){
		$this->stock_total = $valor;
	}	
	function set_stock_minimo($valor){
		$this->stock_minimo = $valor;
	}
	function set_precio($valor){
		$this->precio = $valor;
	}			
	function set_imagen($valor){
		$this->imagen = $valor;
	}
	function set_presentacion_id($valor){
		$this->presentacion_id = $valor;
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
	function get_stock_total(){
		return $this->stock_total;
	}	
	function get_stock_minimo(){
		return $this->stock_minimo;
	}	
	function get_precio(){
		return $this->precio;
	}	
	function get_imagen(){
		return $this->imagen;
	}
	function get_presentacion_id(){
		return $this->presentacion_id;
	}

    // Método para incluir 
	function incluir(){
		$r = array();
        // Verifica si el código ya existe		
		if(!$this->existe($this->codigo)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo insumo en la base de datos				
				$co->query("INSERT INTO insumos(codigo, nombre, marca, cantidad, cantidad_minima, precio, imagen, id_presentacion)
					VALUES(
						'$this->codigo',
						'$this->nombre',
						'$this->marca',
						'$this->stock_total',
						'$this->stock_minimo',						
						'$this->precio',						
						'$this->imagen',
						'$this->presentacion_id'
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
			$r['mensaje'] =  'Ya existe el código del insumo';
		}
		return $r;
	}

	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->codigo)){
			try {
				// Obtiene la imagen actual 			
				$resultado = $co->query("SELECT imagen FROM insumos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];                
				// Actualiza los datos 

				$co->query("UPDATE insumos set 
					codigo = '$this->codigo',
					nombre = '$this->nombre',
					marca = '$this->marca',	
					cantidad = '$this->stock_total',
					cantidad_minima = '$this->stock_minimo',								
					precio = '$this->precio',
					imagen = '$this->imagen',
					id_presentacion = '$this->presentacion_id'
					where
					codigo = '$this->codigo'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro actualizado con éxito!';                
				// Elimina la imagen anterior si es necesario

				if ($imagen_actual && $imagen_actual != 'otros/img/insumos/default.png') {
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


    // Método para eliminar 
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		error_log("Iniciando eliminación del insumo con código: " . $this->codigo);        
		// Verifica si el insumo existe
		if($this->existe($this->codigo)){
			try {                
				// Obtiene la imagen del insumo
				$resultado = $co->query("SELECT imagen FROM insumos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				error_log("Imagen del insumo: " . $imagen);
				
				// Elimina el insumo de la base de datos
				$co->query("DELETE from insumos where codigo = '$this->codigo' ");
				error_log("Insumo eliminado correctamente de la BD");
				
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				
				// Elimina la imagen si existe
				if ($imagen && $imagen != 'otros/img/insumos/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
						error_log("Imagen eliminada: " . $imagen);
					}
				}
			} catch (Exception $e) {
				error_log("Error al eliminar insumo: " . $e->getMessage() . " - Código: " . $e->getCode());
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este insumo porque tiene movimientos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}
		else{
			error_log("El insumo con código " . $this->codigo . " no existe");
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el nombre de documento';
		}
		return $r;
	}

    // Método para consultar 	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
            // Realiza la consulta para obtener insumos y sus detalles
			$resultado = $co->query("SELECT i.codigo, i.nombre, i.marca, i.cantidad as stock_total, i.cantidad_minima as stock_minimo, i.precio, i.imagen, i.id_presentacion, p.nombre as presentacion_nombre
				FROM insumos i 
				LEFT JOIN presentaciones p ON i.id_presentacion = p.id 
				ORDER BY i.id DESC");
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


					$respuesta .= "<td>".$row['stock_total'].
					($row['stock_total'] == 0 ? "<br><span class='badge bg-danger' title='Este producto esta agotado'>No disponible</span>" : " " . 
							($row['stock_total'] <= $row['stock_minimo'] ? "<br><span class='badge bg-warning' title='Este producto llegó al nivel mínimo de stock'>Stock bajo</span>" : " ")) . "</td>";

					$respuesta .= "<td>".$row['stock_minimo']."</td>";
					$respuesta .= "<td data-presentacion-id='".$row['id_presentacion']."'>".$row['presentacion_nombre']."</td>";
					if ($_SESSION['tipo_usuario'] == 'administrador'): 	
						$respuesta .= "<td>$".$row['precio']."</td>";
					endif; 
					$respuesta .= "<td><a href='".$row['imagen']."' target='_blank'><img src='".$row['imagen']."' alt='Imagen del insumo' class='img'/></a></td>";		
					$respuesta .= "<td>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar insumo'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar insumo'><i class='bi bi-trash'></i></button><br/>";
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
					// Verifica si el stock es bajo
					if ($row['stock_total'] <= $row['stock_minimo']) {
						$productos_bajo_stock[] = array(
							'nombre' => $row['nombre'],
							'stock_total' => $row['stock_total'],
							'stock_minimo' => $row['stock_minimo']
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



    // Método privado para verificar si un producto existe	
	private function existe($codigo){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from insumos where codigo='$codigo'");	
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

    // Método para obtener insumos que necesitan notificación		
	function obtenerInsumosNotificacion() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT codigo, nombre, cantidad, cantidad_minima FROM insumos WHERE cantidad <= cantidad_minima OR cantidad = 0");
			$insumos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $insumos;
		} catch(Exception $e) {
			return array();
		}
	}

    // Métodos para obtener presentaciones
	function obtenerPresentaciones() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT id, nombre FROM presentaciones");
			$presentaciones = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $presentaciones;
		} catch(Exception $e) {
			return array();
		}
	}
}
?>