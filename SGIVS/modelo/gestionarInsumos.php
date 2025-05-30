<?php
require_once('modelo/datos.php');
require_once('dompdf/vendor/autoload.php'); 

use Dompdf\Dompdf;

class gestionarInsumos extends datos{
    // Propiedades de la clase	
	private $codigo; 
	private $nombre;
	private $marca;
	private $stock_total;
	private $stock_minimo;	
	private $precio;
	private $imagen;
	private $presentacion_id;
	private $presentacion;
	private $errores = array();

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

	function set_presentacion($valor){
		$this->presentacion = $valor;
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

    // Métodos de validación
    private function validarCodigo($codigo) {
        if (empty($codigo)) {
            $this->errores[] = "El código es obligatorio";
            return false;
        }
        if (strlen($codigo) > 20) {
            $this->errores[] = "El código no puede tener más de 20 caracteres";
            return false;
        }
        if (!preg_match('/^[^"\']{1,20}$/', $codigo)) {
            $this->errores[] = "El código contiene caracteres no permitidos";
            return false;
        }
        return true;
    }

    private function validarNombre($nombre) {
        if (empty($nombre)) {
            $this->errores[] = "El nombre es obligatorio";
            return false;
        }
        if (strlen($nombre) < 3 || strlen($nombre) > 50) {
            $this->errores[] = "El nombre debe tener entre 3 y 50 caracteres";
            return false;
        }
        return true;
    }

    private function validarMarca($marca) {
        if (!empty($marca)) {
            if (strlen($marca) > 35) {
                $this->errores[] = "La marca no puede tener más de 35 caracteres";
                return false;
            }
            if (!preg_match('/^[^"\']{1,35}$/', $marca)) {
                $this->errores[] = "La marca contiene caracteres no permitidos";
                return false;
            }
        }
        return true;
    }

    private function validarStock($stock) {
        if (!is_numeric($stock)) {
            $this->errores[] = "El stock debe ser un número entero";
            return false;
        }
        if (!preg_match('/^[0-9]{1,10}$/', $stock)) {
            $this->errores[] = "El stock debe ser un número entero válido";
            return false;
        }
        return true;
    }

    private function validarPrecio($precio) {
        if (!is_numeric($precio)) {
            $this->errores[] = "El precio debe ser un número";
            return false;
        }
        if (!preg_match('/^[0-9.]{1,10}$/', $precio)) {
            $this->errores[] = "El precio debe ser un número válido";
            return false;
        }
        return true;
    }

    private function validarPresentacion($presentacion_id) {
        if (empty($presentacion_id)) {
            $this->errores[] = "La presentación es obligatoria";
            return false;
        }
        if (!is_numeric($presentacion_id)) {
            $this->errores[] = "ID de presentación inválido";
            return false;
        }
        // Verificar si la presentación existe
        $co = $this->conecta();
        try {
            $resultado = $co->query("SELECT id FROM presentaciones WHERE id = '$presentacion_id'");
            if (!$resultado->fetch()) {
                $this->errores[] = "La presentación seleccionada no existe";
                return false;
            }
        } catch(Exception $e) {
            $this->errores[] = "Error al validar la presentación";
            return false;
        } finally {
            $co = null;
        }
        return true;
    }

    private function validarDatos() {
        $this->errores = array();
        
        $valido = true;
        $valido = $this->validarCodigo($this->codigo) && $valido;
        $valido = $this->validarNombre($this->nombre) && $valido;
        $valido = $this->validarMarca($this->marca) && $valido;
        $valido = $this->validarStock($this->stock_total) && $valido;
        $valido = $this->validarStock($this->stock_minimo) && $valido;
        $valido = $this->validarPrecio($this->precio) && $valido;
        $valido = $this->validarPresentacion($this->presentacion_id) && $valido;

        if ($this->stock_minimo > $this->stock_total) {
            $this->errores[] = "El stock mínimo no puede ser mayor que el stock total";
            $valido = false;
        }

        return $valido;
    }

    // Método para incluir 
	function incluir(){
		$r = array();
        
        if (!$this->validarDatos()) {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = implode(", ", $this->errores);
            $this->registrarBitacora('Insumos', 'Incluir', 'Error al intentar incluir insumo', implode(", ", $this->errores));
            return $r;
        }

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
				$this->registrarBitacora('Insumos', 'Incluir', 'Insumo incluido exitosamente', 
					"Código: $this->codigo, Nombre: $this->nombre, Marca: $this->marca");
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
				$this->registrarBitacora('Insumos', 'Incluir', 'Error al incluir insumo', $e->getMessage());
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el código del insumo';
			$this->registrarBitacora('Insumos', 'Incluir', 'Error: Código de insumo duplicado', "Código: $this->codigo");
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
				$this->registrarBitacora('Insumos', 'Modificar', 'Insumo modificado exitosamente', 
					"Código: $this->codigo, Nombre: $this->nombre, Marca: $this->marca");
				// Elimina la imagen anterior si es necesario
				if ($imagen_actual && $imagen_actual != 'otros/img/insumos/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
				$this->registrarBitacora('Insumos', 'Modificar', 'Error al modificar insumo', $e->getMessage());
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Codigo no registrado';
			$this->registrarBitacora('Insumos', 'Modificar', 'Error: Código de insumo no encontrado', "Código: $this->codigo");
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
				$resultado = $co->query("SELECT imagen, nombre, marca FROM insumos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				$nombre = $fila['nombre'];
				$marca = $fila['marca'];
				error_log("Imagen del insumo: " . $imagen);
				
				// Elimina el insumo de la base de datos
				$co->query("DELETE from insumos where codigo = '$this->codigo' ");
				error_log("Insumo eliminado correctamente de la BD");
				
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				$this->registrarBitacora('Insumos', 'Eliminar', 'Insumo eliminado exitosamente', 
					"Código: $this->codigo, Nombre: $nombre, Marca: $marca");
				
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
				$this->registrarBitacora('Insumos', 'Eliminar', 'Error al eliminar insumo', $e->getMessage());
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			error_log("El insumo con código " . $this->codigo . " no existe");
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el nombre de documento';
			$this->registrarBitacora('Insumos', 'Eliminar', 'Error: Código de insumo no encontrado', "Código: $this->codigo");
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
				$insumos = array();
				$productos_bajo_stock = array();
				$n = 1;
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
					$insumo = array(
						'numero' => $n,
						'codigo' => $row['codigo'],
						'nombre' => $row['nombre'],
						'marca' => $row['marca'],
						'stock_total' => $row['stock_total'],
						'stock_minimo' => $row['stock_minimo'],
						'precio' => $row['precio'],
						'imagen' => $row['imagen'],
						'presentacion_id' => $row['id_presentacion'],
						'presentacion_nombre' => $row['presentacion_nombre']
					);
					$insumos[] = $insumo;

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
				$r['datos'] = $insumos;
				$r['productos_bajo_stock'] = $productos_bajo_stock;
			} else {
				$r['resultado'] = 'consultar';
				$r['datos'] = array();
				$r['productos_bajo_stock'] = array();
			}
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		} finally {
			$co = null; // Cierra la conexión
		}
		return $r;
	}


	function consultar2() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("
				SELECT ei.id, ei.fecha_entrada,
				i.codigo, i.nombre AS insumo_nombre, ie.cantidad, ie.precio, 
				(ie.cantidad * ie.precio) AS subtotal,
				SUM(ie.cantidad * ie.precio) OVER (PARTITION BY ei.id) AS total
				FROM entradas_insumos ei
				JOIN insumos_entradas ie ON ei.id = ie.id_entradas_insumos
				JOIN insumos i ON ie.id_insumos = i.id
				ORDER BY ei.fecha_entrada DESC;
			");

			if ($resultado) {
				$entradas = array();
				$n = 1;
				$currentEntrada = null;
				$entradaActual = null;

				foreach ($resultado as $row) {
					if ($currentEntrada !== $row['id']) {
						if ($entradaActual !== null) {
							$entradas[] = $entradaActual;
						}
						

						$entradaActual = array(
							'numero' => $n,
							'id' => $row['id'],
							'fecha_entrada' => $row['fecha_entrada'],
							'total' => $row['total'],
							'detalles' => array()
						);
						
						$currentEntrada = $row['id'];
						$n++;
					}

					$entradaActual['detalles'][] = array(
						'codigo' => $row['codigo'],
						'nombre' => $row['insumo_nombre'],
						'cantidad' => $row['cantidad'],
						'precio' => $row['precio'],
						'subtotal' => $row['subtotal']
					);
				}

				// Agregar la última entrada
				if ($entradaActual !== null) {
					$entradas[] = $entradaActual;
				}

				$r['resultado'] = 'consultar2';
				$r['datos'] = $entradas;
			} else {
				$r['resultado'] = 'consultar2';
				$r['datos'] = array();
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		} finally {
			$co = null; // Cierra la conexión
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
			return $fila ? true : false;
		} catch(Exception $e){
			return false;
		} finally {
			$co = null; // Cierra la conexión
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
		} finally {
			$co = null; // Cierra la conexión
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
		} finally {
			$co = null; // Cierra la conexión
		}
	}


	function entrada( $id_insumo, $cantidad, $precio, $imagen = null) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		// Validar arrays de entrada
		if (empty($id_insumo) || empty($cantidad) || empty($precio)) {
			$r['resultado'] = 'error';
			$r['mensaje'] = 'Los datos de entrada son obligatorios';
			$this->registrarBitacora('Insumos', 'Entrada', 'Error: Datos de entrada incompletos');
			return $r;
		}

		// Convertir strings a arrays si es necesario
		if (!is_array($id_insumo)) {
			$id_insumo = explode(',', $id_insumo);
		}
		if (!is_array($cantidad)) {
			$cantidad = explode(',', $cantidad);
		}
		if (!is_array($precio)) {
			$precio = explode(',', $precio);
		}

		// Validar que los arrays tengan la misma longitud
		if (count($id_insumo) !== count($cantidad) || count($id_insumo) !== count($precio)) {
			$r['resultado'] = 'error';
			$r['mensaje'] = 'Los datos de entrada están incompletos';
			$this->registrarBitacora('Insumos', 'Entrada', 'Error: Arrays de entrada con longitudes diferentes');
			return $r;
		}

		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');
			$fecha = date('Y-m-d H:i:s');
			
			// Validar cada insumo antes de procesar
			foreach ($id_insumo as $i => $id) {
				if (!is_numeric($cantidad[$i]) || $cantidad[$i] <= 0) {
					throw new Exception("La cantidad debe ser mayor a 0 para todos los insumos");
				}
				if (!is_numeric($precio[$i]) || $precio[$i] <= 0) {
					throw new Exception("El precio debe ser mayor a 0 para todos los insumos");
				}
				
				// Verificar si el insumo existe
				$resultado = $co->query("SELECT codigo, nombre FROM insumos WHERE id = '$id'");
				$insumo = $resultado->fetch(PDO::FETCH_ASSOC);
				if (!$insumo) {
					throw new Exception("Uno o más insumos no existen en el sistema");
				}
			}

			$co->query("INSERT INTO entradas_insumos(fecha_entrada) VALUES ('$fecha')");
			$lid = $co->lastInsertId();
			
			$detalles_entrada = array();
			$tamano = count($id_insumo);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO insumos_entradas (id_entradas_insumos, id_insumos, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $id_insumo[$i], $cantidad[$i], $precio[$i]]);
				
				// Actualizar el stock y el precio de compra del insumo
				$actualizaInsumo = $co->prepare("UPDATE insumos SET cantidad = cantidad + ?, precio = ? WHERE id = ?");
				$actualizaInsumo->execute([$cantidad[$i], $precio[$i], $id_insumo[$i]]);

				// Obtener detalles del insumo para la bitácora
				$resultado = $co->query("SELECT codigo, nombre FROM insumos WHERE id = '$id_insumo[$i]'");
				$insumo = $resultado->fetch(PDO::FETCH_ASSOC);
				$detalles_entrada[] = "Insumo: {$insumo['codigo']} - {$insumo['nombre']}, Cantidad: {$cantidad[$i]}, Precio: {$precio[$i]}";
			}   
			$co->commit();
			$r['resultado'] = 'entrada';
			$r['mensaje'] = '¡Entrada registrada con éxito!';
			$this->registrarBitacora('Insumos', 'Entrada', 'Entrada de insumos registrada exitosamente', 
				"ID Entrada: $lid\n" . implode("\n", $detalles_entrada));
		} catch(Exception $e) {
			$co->rollback();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
			$this->registrarBitacora('Insumos', 'Entrada', 'Error al registrar entrada de insumos', $e->getMessage());
		} finally {
			$co = null; // Cierra la conexión
		}
		return $r;
	}

	function listadoInsumos(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("SELECT i.id, i.codigo, i.nombre, i.marca, i.cantidad, i.cantidad_minima, 
				i.precio, i.imagen, i.id_presentacion, p.nombre as presentacion_nombre 
				FROM insumos i 
				LEFT JOIN presentaciones p ON i.id_presentacion = p.id");
			if($resultado){
				$insumos = array();
				foreach($resultado as $row){
					$insumo = array(
						'id' => $row['id'],
						'codigo' => $row['codigo'],
						'nombre' => $row['nombre'],
						'marca' => $row['marca'],
						'cantidad' => $row['cantidad'],
						'cantidad_minima' => $row['cantidad_minima'],
						'precio' => $row['precio'],
						'presentacion_nombre' => $row['presentacion_nombre'],
						'imagen' => $row['imagen']
					);
					$insumos[] = $insumo;
				}
				$r['resultado'] = 'listadoInsumos';
				$r['datos'] = $insumos;
			} else {
				$r['resultado'] = 'listadoInsumos';
				$r['datos'] = array();
			}
		} catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		} finally {
			$co = null; // Cierra la conexión
		}
		return $r;
	}	

	function reporte_insumos(){    
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("SELECT i.codigo, i.nombre, i.marca, i.cantidad as stock_total, i.cantidad_minima as stock_minimo, i.precio, p.nombre as presentacion_nombre FROM insumos i LEFT JOIN presentaciones p ON i.id_presentacion = p.id WHERE i.codigo like :codigo and i.nombre like :nombre and i.marca like :marca and i.cantidad like :stock_total and i.cantidad_minima like :stock_minimo and i.precio like :precio and p.nombre like :presentacion");
			$resultado->bindValue(':codigo','%'.$this->codigo.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':marca','%'.$this->marca.'%');
			$resultado->bindValue(':stock_total','%'.$this->stock_total.'%');
			$resultado->bindValue(':stock_minimo','%'.$this->stock_minimo.'%');
			$resultado->bindValue(':precio','%'.$this->precio.'%');
			$resultado->bindValue(':presentacion','%'.$this->presentacion.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>Centro Odontologico Vital Sonrisa, C.A<br>J-</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Dirección: </strong>Calle 39 entre Carreras 20 y 21, Edificio La Princesa, Piso 1, Consultorio 7 / Barquisimeto.<br><strong>Telefono:</strong> 0414-1570548.<br><strong>Fecha:</strong> ".$fecha.".</p>";	






			$html = $html . "<div style='background-color: #38bdde; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Insumos</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #38bdde; '>";
			$html = $html."<th style='border: solid;'>#</th>";    
			$html = $html."<th style='border: solid;'>Codigo</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Marca</th>";
			$html = $html."<th style='border: solid;'>Stock total</th>";
			$html = $html."<th style='border: solid;'>Stock minimo</th>";
			$html = $html."<th style='border: solid;'>Precio</th>";
			$html = $html."<th style='border: solid;'>Presentación</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['codigo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['marca']."</td>";
					$html = $html."<td style='border: solid;'>".$f['stock_total']."</td>";
					$html = $html."<td style='border: solid;'>".$f['stock_minimo']."</td>";
					$html = $html."<td style='border: solid;'>$".$f['precio']."</td>";
					$html = $html."<td style='border: solid;'>".$f['presentacion_nombre']."</td>";
					$html = $html."</tr>";
					$n++;
				}
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";    
		}catch(Exception $e){
		}
		$fecha = date('d-m-y');        
		$pdf = new DOMPDF();
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteInsumos".$fecha.".pdf", array("Attachment" => false));    
	}

}
?>