<?php
require_once('modelo/datos.php');

class entradasInsumos extends datos{
	
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("
				SELECT ei.id, ei.fecha_entrada,
				i.codigo, i.nombre 
				AS insumo_nombre, ie.cantidad, ie.precio, (ie.cantidad * ie.precio) AS subtotal,
				SUM(ie.cantidad * ie.precio) OVER (PARTITION BY ei.id) AS total
				FROM entradas_insumos ei
				JOIN insumos_entradas ie ON ei.id = ie.id_entradas_insumos
				JOIN insumos i ON ie.id_insumos = i.id
				ORDER BY ei.fecha_entrada DESC;
				");
			if ($resultado) {
				$respuesta = '';
				$n = 1;
				$currentSalida = null;
				$prevTotal = 0;
				foreach ($resultado as $r) {
					if ($currentSalida !== $r['id']) {
						if ($currentSalida !== null) {
							$respuesta .= "</tbody></table></td>";
							$respuesta .= "<td class='align-middle'>$" . number_format($prevTotal, 2) . "</td>";
							$respuesta .= "</tr>";
						}
						$prevTotal = $r['total'];
						$respuesta .= "<tr class='text-center'>";
						$respuesta .= "<td class='align-middle'>" . $n . "</td>";
						$respuesta .= "<td class='align-middle'>" . $r['fecha_entrada'] . "</td>";
						
						$respuesta .= "<td class='align-middle'><table class='table table-sm'><thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>";
						$currentSalida = $r['id'];
						$n++;
					}
					
					$respuesta .= "<tr>";
					$respuesta .= "<td>" . $r['codigo'] . "</td>";
					$respuesta .= "<td>" . $r['insumo_nombre'] . "</td>";
					$respuesta .= "<td>" . $r['cantidad'] . "</td>";
					$respuesta .= "<td>$" . number_format($r['precio'], 2) . "</td>";
					$respuesta .= "<td>$" . number_format($r['subtotal'], 2) . "</td>";
					$respuesta .= "</tr>";
				}
				if ($currentSalida !== null) {
					$respuesta .= "</tbody></table></td>";
					$respuesta .= "<td class='align-middle'>$" . number_format($prevTotal, 2) . "</td>";
					$respuesta .= "</tr>";
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] = $respuesta;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] = '';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	function entrada( $id_insumo, $cantidad, $precio, $imagen = null) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('Y-m-d H:i:s');
			
			$guarda = $co->prepare("INSERT INTO entradas_insumos(fecha_entrada) VALUES (?)");
			$guarda->execute([$fecha]);
			
			$lid = $co->lastInsertId();
			
			// Convertir strings separados por comas a arrays si es necesario
			if (!is_array($id_insumo)) {
				$id_insumo = explode(',', $id_insumo);
			}
			if (!is_array($cantidad)) {
				$cantidad = explode(',', $cantidad);
			}
			if (!is_array($precio)) {
				$precio = explode(',', $precio);
			}
			
			$tamano = count($id_insumo);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO insumos_entradas (id_entradas_insumos, id_insumos, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $id_insumo[$i], $cantidad[$i], $precio[$i]]);
				
				// Actualizar el stock y el precio de compra del insumo
				$actualizaInsumo = $co->prepare("UPDATE insumos SET cantidad = cantidad + ?, precio = ? WHERE id = ?");
				$actualizaInsumo->execute([$cantidad[$i], $precio[$i], $id_insumo[$i]]);
			}   
			$co->commit();
			$r['resultado'] = 'entrada';
			$r['mensaje'] = '¡Entrada registrada con éxito!';
		} catch(Exception $e) {
			$co->rollback();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
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
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='colocaInsumo(this);'>";
					$respuesta = $respuesta."<td style='display:none'>";
					$respuesta = $respuesta.$r['id'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['codigo'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['nombre'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['marca'];
					$respuesta = $respuesta."</td>";
					$respuesta .= "<td>".$r['cantidad'].
					($r['cantidad'] == 0 ? "<span class='badge bg-danger' title='Este insumo esta agotado'>No disponible</span>" : " " . 
						($r['cantidad'] <= $r['cantidad_minima'] ? "<br><span class='badge bg-warning' title='Este insumo llegó al nivel mínimo de stock'>Stock bajo</span>" : " ")) . "</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['cantidad_minima'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['precio'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['presentacion_nombre'];
					$respuesta = $respuesta."</td>";	
					$respuesta = $respuesta . "<td class='align-middle'><img src='" . $r['imagen'] . "' alt='Imagen del insumo' class='img'/></td>";	
					$respuesta = $respuesta."</tr>";
				}	  
			}
			$r['resultado'] = 'listadoInsumos';
			$r['mensaje'] =  $respuesta;
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}	

}
