<?php
require_once('modelo/datos.php');

class entradasEquipos extends datos{
	
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$respuesta_final = array();
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
						$respuesta .= "<td class='align-middle'>";
						if ($r['nota_entrega']) {
							$respuesta .= "<a href='" . $r['nota_entrega'] . "' target='_blank'>Ver nota</a>";
						} else {
							$respuesta .= "No disponible";
						}
						$respuesta .= "</td>";
						
						$respuesta .= "<td class='align-middle'><table class='table table-sm'><thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>";
						$currentSalida = $r['id'];
						$n++;
					}
					
					$respuesta .= "<tr>";
					$respuesta .= "<td>" . $r['codigo'] . "</td>";
					$respuesta .= "<td>" . $r['equipo_nombre'] . "</td>";
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
				$respuesta_final['resultado'] = 'consultar';
				$respuesta_final['mensaje'] = $respuesta;
			} else {
				$respuesta_final['resultado'] = 'consultar';
				$respuesta_final['mensaje'] = '';
			}
		} catch (Exception $e) {
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] = $e->getMessage();
		}
		return $respuesta_final;
	}

	function entrada( $id_equipo, $cantidad, $precio, $imagen = null) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$respuesta_final = array();
		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('Y-m-d H:i:s');
			
			$guarda = $co->prepare("INSERT INTO entradas_equipos(fecha_entrada,nota_entrega) VALUES (?, ?)");
			$guarda->execute([$fecha,$imagen]);
			
			$lid = $co->lastInsertId();
			$tamano = count($id_equipo);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO entradas_equipos_detalles (id_entrada_equipo, id_equipo, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $id_equipo[$i], $cantidad[$i], $precio[$i]]);
				
				// Actualizar el stock y el precio de compra del equipo
				$actualizaEquipo = $co->prepare("UPDATE equipos SET cantidad = cantidad + ?, precio = ? WHERE id = ?");
				$actualizaEquipo->execute([$cantidad[$i], $precio[$i], $id_equipo[$i]]);
			}   
			$co->commit();
			$respuesta_final['resultado'] = 'entrada';
			$respuesta_final['mensaje'] = '¡Entrada registrada con éxito!';
		} catch(Exception $e) {
			$co->rollback();
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] = $e->getMessage();
		}
		return $respuesta_final;
	}

	function listadoEquipos(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$respuesta_final = array();
		try{
			$resultado = $co->query("Select * from equipos");
			if($resultado){
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='colocaEquipo(this);'>";
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
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['modelo'];
					$respuesta = $respuesta."</td>";
					$respuesta .= "<td>".$r['cantidad'].
					($r['cantidad'] == 0 ? "<br><span class='badge bg-danger' title='Este equipo esta agotado'>No disponible</span>" : " ") . "</td>";	
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['precio'];
					$respuesta = $respuesta."</td>";							
					$respuesta = $respuesta . "<td class='align-middle'><img src='" . $r['imagen'] . "' alt='Imagen del equipo' class='img'/></td>";					
					$respuesta = $respuesta."</tr>";
				}	  
			}
			$respuesta_final['resultado'] = 'listadoEquipos';
			$respuesta_final['mensaje'] =  $respuesta;
			
		}catch(Exception $e){
			$respuesta_final['resultado'] = 'error';
			$respuesta_final['mensaje'] =  $e->getMessage();
		}
		return $respuesta_final;
	}	

}
