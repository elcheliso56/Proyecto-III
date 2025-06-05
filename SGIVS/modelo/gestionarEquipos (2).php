<?php
require_once('modelo/datos.php');
require_once('dompdf/vendor/autoload.php'); 

use Dompdf\Dompdf;

class gestionarEquipos extends datos{
	
    // Propiedades de la clase	
	private $codigo; 
	private $nombre;
	private $marca;
	private $modelo;
	private $cantidad;
	private $precio;
	private $imagen;
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
	function set_modelo($valor){
		$this->modelo = $valor;
	}	
	function set_cantidad($valor){
		$this->cantidad = $valor;
	}
	function set_precio($valor){
		$this->precio = $valor;
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
	function get_precio(){
		return $this->precio;
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
            if (strlen($marca) > 50) {
                $this->errores[] = "La marca no puede tener más de 50 caracteres";
                return false;
            }
            if (!preg_match('/^[^"\']{1,50}$/', $marca)) {
                $this->errores[] = "La marca contiene caracteres no permitidos";
                return false;
            }
        }
        return true;
    }

    private function validarModelo($modelo) {
        if (!empty($modelo)) {
            if (strlen($modelo) > 50) {
                $this->errores[] = "El modelo no puede tener más de 50 caracteres";
                return false;
            }
            if (!preg_match('/^[^"\']{1,50}$/', $modelo)) {
                $this->errores[] = "El modelo contiene caracteres no permitidos";
                return false;
            }
        }
        return true;
    }

    private function validarCantidad($cantidad) {
        if (!is_numeric($cantidad)) {
            $this->errores[] = "La cantidad debe ser un número entero";
            return false;
        }
        if (!preg_match('/^[0-9]{1,10}$/', $cantidad)) {
            $this->errores[] = "La cantidad debe ser un número entero válido";
            return false;
        }
        if ($cantidad < 0) {
            $this->errores[] = "La cantidad no puede ser negativa";
            return false;
        }
        return true;
    }



    private function validarDatos() {
        $this->errores = array();
        
        $valido = true;
        $valido = $this->validarCodigo($this->codigo) && $valido;
        $valido = $this->validarNombre($this->nombre) && $valido;
        $valido = $this->validarMarca($this->marca) && $valido;
        $valido = $this->validarModelo($this->modelo) && $valido;
        $valido = $this->validarCantidad($this->cantidad) && $valido;

        return $valido;
    }

    // Método para incluir un nuevo equipo
	function incluir(){
		$r = array();
        
        if (!$this->validarDatos()) {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = implode(", ", $this->errores);
            $this->registrarBitacora('Equipos', 'Incluir', 'Error al intentar incluir equipo', implode(", ", $this->errores));
            return $r;
        }

        // Verifica si el código ya existe		
		if(!$this->existe($this->codigo)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo equipo en la base de datos				
				$co->query("INSERT INTO equipos(codigo, nombre, marca, modelo, cantidad, precio, imagen)
					VALUES(
						'$this->codigo',
						'$this->nombre',
						'$this->marca',
						'$this->modelo',
						'$this->cantidad',
						'$this->precio',
						'$this->imagen'
					)");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con éxito!';
				$this->registrarBitacora('Equipos', 'Incluir', 'Equipo incluido exitosamente', 
					"Código: $this->codigo, Nombre: $this->nombre, Marca: $this->marca, Modelo: $this->modelo");
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
				$this->registrarBitacora('Equipos', 'Incluir', 'Error al incluir equipo', $e->getMessage());
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el código del equipo';
			$this->registrarBitacora('Equipos', 'Incluir', 'Error: Código de equipo duplicado', "Código: $this->codigo");
		}
		return $r;
	}

    // Método para modificar un equipo existente
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

        if (!$this->validarDatos()) {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = implode(", ", $this->errores);
            $this->registrarBitacora('Equipos', 'Modificar', 'Error al intentar modificar equipo', implode(", ", $this->errores));
            return $r;
        }

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
					precio = '$this->precio',
					imagen = '$this->imagen'
					where
					codigo = '$this->codigo'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro actualizado con éxito!';                
				$this->registrarBitacora('Equipos', 'Modificar', 'Equipo modificado exitosamente', 
					"Código: $this->codigo, Nombre: $this->nombre, Marca: $this->marca, Modelo: $this->modelo");
				// Elimina la imagen anterior si es necesario
				if ($imagen_actual && $imagen_actual != 'otros/img/equipos/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
				$this->registrarBitacora('Equipos', 'Modificar', 'Error al modificar equipo', $e->getMessage());
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Codigo no registrado';
			$this->registrarBitacora('Equipos', 'Modificar', 'Error: Código de equipo no encontrado', "Código: $this->codigo");
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
				// Obtiene la imagen y datos del equipo a eliminar
				$resultado = $co->query("SELECT imagen, nombre, marca, modelo FROM equipos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				$nombre = $fila['nombre'];
				$marca = $fila['marca'];
				$modelo = $fila['modelo'];
				// Elimina el equipo de la base de datos
				$co->query("DELETE from equipos where codigo = '$this->codigo' ");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				$this->registrarBitacora('Equipos', 'Eliminar', 'Equipo eliminado exitosamente', 
					"Código: $this->codigo, Nombre: $nombre, Marca: $marca, Modelo: $modelo");
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
				$this->registrarBitacora('Equipos', 'Eliminar', 'Error al eliminar equipo', $e->getMessage());
			} finally {
				$co = null; // Cierra la conexión
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el código del equipo';
			$this->registrarBitacora('Equipos', 'Eliminar', 'Error: Código de equipo no encontrado', "Código: $this->codigo");
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
				$equipos = array();
				$productos_bajo_stock = array();
				$n = 1;
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
					$equipo = array(
						'numero' => $n,
						'codigo' => $row['codigo'],
						'nombre' => $row['nombre'],
						'marca' => $row['marca'],
						'modelo' => $row['modelo'],
						'cantidad' => $row['cantidad'],
						'precio' => $row['precio'],
						'imagen' => $row['imagen']
					);
					$equipos[] = $equipo;

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
				$r['datos'] = $equipos;
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
		} finally {
			$co = null; // Cierra la conexión
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
		} finally {
			$co = null; // Cierra la conexión
		}
	}

	function consultar2() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("
				SELECT ee.id, ee.fecha_entrada, ee.nota_entrega,
				e.codigo, e.nombre AS equipo_nombre, eed.cantidad, eed.precio, (eed.cantidad * eed.precio) AS subtotal,
				SUM(eed.cantidad * eed.precio) OVER (PARTITION BY ee.id) AS total
				FROM entradas_equipos ee
				JOIN entradas_equipos_detalles eed ON ee.id = eed.id_entrada_equipo
				JOIN equipos e ON eed.id_equipo = e.id
				ORDER BY ee.fecha_entrada DESC;
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
							'nota_entrega' => $row['nota_entrega'],
							'total' => $row['total'],
							'detalles' => array()
						);
						
						$currentEntrada = $row['id'];
						$n++;
					}

					$entradaActual['detalles'][] = array(
						'codigo' => $row['codigo'],
						'nombre' => $row['equipo_nombre'],
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

	function entrada( $id_equipo, $cantidad, $precio, $imagen = null) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$respuesta_final = array();

		// Validar arrays de entrada
		if (empty($id_equipo) || empty($cantidad) || empty($precio)) {
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] = 'Los datos de entrada son obligatorios';
			$this->registrarBitacora('Equipos', 'Entrada', 'Error: Datos de entrada incompletos');
			return $respuesta_final;
		}

		// Convertir strings a arrays si es necesario
		if (!is_array($id_equipo)) {
			$id_equipo = explode(',', $id_equipo);
		}
		if (!is_array($cantidad)) {
			$cantidad = explode(',', $cantidad);
		}
		if (!is_array($precio)) {
			$precio = explode(',', $precio);
		}

		// Validar que los arrays tengan la misma longitud
		if (count($id_equipo) !== count($cantidad) || count($id_equipo) !== count($precio)) {
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] = 'Los datos de entrada están incompletos';
			$this->registrarBitacora('Equipos', 'Entrada', 'Error: Arrays de entrada con longitudes diferentes');
			return $respuesta_final;
		}

		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');
			$fecha = date('Y-m-d H:i:s');
			
			// Validar cada equipo antes de procesar
			foreach ($id_equipo as $i => $id) {
				if (!is_numeric($cantidad[$i]) || $cantidad[$i] <= 0) {
					throw new Exception("La cantidad debe ser mayor a 0 para todos los equipos");
				}
				if (!is_numeric($precio[$i]) || $precio[$i] <= 0) {
					throw new Exception("El precio debe ser mayor a 0 para todos los equipos");
				}
				
				// Verificar si el equipo existe
				$resultado = $co->query("SELECT codigo, nombre FROM equipos WHERE id = '$id'");
				$equipo = $resultado->fetch(PDO::FETCH_ASSOC);
				if (!$equipo) {
					throw new Exception("Uno o más equipos no existen en el sistema");
				}
			}

			$co->query("INSERT INTO entradas_equipos(fecha_entrada, nota_entrega) VALUES ('$fecha', '$imagen')");
			$lid = $co->lastInsertId();
			
			$detalles_entrada = array();
			$tamano = count($id_equipo);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO entradas_equipos_detalles (id_entrada_equipo, id_equipo, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $id_equipo[$i], $cantidad[$i], $precio[$i]]);
				
				// Actualizar el stock y el precio de compra del equipo
				$actualizaEquipo = $co->prepare("UPDATE equipos SET cantidad = cantidad + ?, precio = ? WHERE id = ?");
				$actualizaEquipo->execute([$cantidad[$i], $precio[$i], $id_equipo[$i]]);

				// Obtener detalles del equipo para la bitácora
				$resultado = $co->query("SELECT codigo, nombre FROM equipos WHERE id = '$id_equipo[$i]'");
				$equipo = $resultado->fetch(PDO::FETCH_ASSOC);
				$detalles_entrada[] = "Equipo: {$equipo['codigo']} - {$equipo['nombre']}, Cantidad: {$cantidad[$i]}, Precio: {$precio[$i]}";
			}   
			$co->commit();
			$respuesta_final['resultado'] = 'entrada';
			$respuesta_final['mensaje'] = '¡Entrada registrada con éxito!';
			$this->registrarBitacora('Equipos', 'Entrada', 'Entrada de equipos registrada exitosamente', 
				"ID Entrada: $lid\n" . implode("\n", $detalles_entrada));
		} catch(Exception $e) {
			$co->rollback();
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] = $e->getMessage();
			$this->registrarBitacora('Equipos', 'Entrada', 'Error al registrar entrada de equipos', $e->getMessage());
		} finally {
			$co = null; // Cierra la conexión
		}
		return $respuesta_final;
	}

	function listadoEquipos(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("Select * from equipos");
			if($resultado){
				$equipos = array();
				foreach($resultado as $row){
					$equipo = array(
						'id' => $row['id'],
						'codigo' => $row['codigo'],
						'nombre' => $row['nombre'],
						'marca' => $row['marca'],
						'modelo' => $row['modelo'],
						'cantidad' => $row['cantidad'],
						'precio' => $row['precio'],
						'imagen' => $row['imagen']
					);
					$equipos[] = $equipo;
				}
				$r['resultado'] = 'listadoEquipos';
				$r['datos'] = $equipos;
			} else {
				$r['resultado'] = 'listadoEquipos';
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

	function reporte_equipos(){    
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');
			$fecha = date('d-m-y H:i');
			
			$resultado = $co->prepare("SELECT codigo, nombre, marca, modelo, cantidad, precio FROM equipos WHERE codigo like :codigo and nombre like :nombre and marca like :marca and modelo like :modelo and cantidad like :cantidad and precio like :precio");
			$resultado->bindValue(':codigo','%'.$this->codigo.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':marca','%'.$this->marca.'%');
			$resultado->bindValue(':modelo','%'.$this->modelo.'%');
			$resultado->bindValue(':cantidad','%'.$this->cantidad.'%');
			$resultado->bindValue(':precio','%'.$this->precio.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);

			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>Centro Odontologico Vital Sonrisa, C.A<br>J-</h2>
			</div>";		
		$html = $html . "<p style='color: #14345a;'><strong>Dirección: </strong>Calle 39 entre Carreras 20 y 21, Edificio La Princesa, Piso 1, Consultorio 7 / Barquisimeto.<br><strong>Telefono:</strong> 0414-1570548.<br><strong>Fecha:</strong> ".$fecha.".</p>";	

			$html = $html . "<div style='background-color: #38bdde; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Equipos</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #38bdde; '>";
			$html = $html."<th style='border: solid;'>#</th>";    
			$html = $html."<th style='border: solid;'>Codigo</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Marca</th>";
			$html = $html."<th style='border: solid;'>Modelo</th>";
			$html = $html."<th style='border: solid;'>Cantidad</th>";
			$html = $html."<th style='border: solid;'>Precio</th>";
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".htmlspecialchars($f['codigo'])."</td>";
					$html = $html."<td style='border: solid;'>".htmlspecialchars($f['nombre'])."</td>";
					$html = $html."<td style='border: solid;'>".htmlspecialchars($f['marca'])."</td>";
					$html = $html."<td style='border: solid;'>".htmlspecialchars($f['modelo'])."</td>";
					$html = $html."<td style='border: solid;'>".htmlspecialchars($f['cantidad'])."</td>";
					$html = $html."<td style='border: solid;'>$".number_format($f['precio'], 2, ',', '.')."</td>";
					$html = $html."</tr>";
					$n++;
				}
			}
			$html = $html."</tbody>";
			$html = $html."</table>";
			$html = $html."</div></div></div>";
			$html = $html."</body></html>";

		} catch(Exception $e) {
			$html = '
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			</head>
			<body>
				<h1>Error al generar el reporte</h1>
				<p>'.htmlspecialchars($e->getMessage()).'</p>
			</body>
			</html>';
		}

		$fecha = date('d-m-y');
		
		$options = new \Dompdf\Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isPhpEnabled', true);
		$options->set('isRemoteEnabled', true);
		
		$pdf = new DOMPDF($options);
		$pdf->set_paper("letter", "portrait");
		$pdf->load_html(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$pdf->render();
		$pdf->stream("ReporteEquipos_".$fecha.".pdf", array("Attachment" => false));
	}

}
