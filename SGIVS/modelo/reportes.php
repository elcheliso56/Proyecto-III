<?php
require_once('dompdf/vendor/autoload.php'); 

use Dompdf\Dompdf; 

require_once('modelo/datos.php');

class reportes extends datos{	
	private $nombre_categoria; 
	private $descripcion_categoria;		
	private $nombre; 
	private $descripcion;	
	private $numero_documento; 
	private $direccion; 
	private $correo;
	private $telefono; 
	private $catalogo;		
	private $codigo; 
	private $precio_compra;
	private $precio_venta;
	private $stock_total;
	private $stock_minimo;
	private $marca;
	private $modelo; 
	private $tipo_unidad;
	private $apellido;
	private $cedula; 
	private $nombre_usuario;
	private $tipo_usuario;
	private $fecha;
	private $cliente;
	private $proveedor;
	private $fecha_inicio;
	private $fecha_fin;
	private $precio;
	private $presentacion;

	function set_nombre_categoria($valor){
		$this->nombre_categoria = $valor; 
	}

	function set_descripcion_categoria($valor){
		$this->descripcion_categoria = $valor;
	}

	function set_nombre($valor){
		$this->nombre = $valor; 
	}

	function set_descripcion($valor){
		$this->descripcion = $valor;
	}

	function set_numero_documento($valor){
		$this->numero_documento = $valor; 
	}

	function set_direccion($valor){
		$this->direccion = $valor; 
	}

	function set_correo($valor){
		$this->correo = $valor; 
	}

	function set_telefono($valor){
		$this->telefono = $valor; 
	}

	function set_catalogo($valor){
		$this->catalogo = $valor; 
	}

	function set_codigo($valor){
		$this->codigo = $valor; 
	}

	function set_precio_compra($valor){
		$this->precio_compra = $valor;
	}

	function set_precio_venta($valor){
		$this->precio_venta = $valor; 
	}



	function set_stock_total($valor){
		$this->stock_total = $valor; 
	}

	function set_stock_minimo($valor){
		$this->stock_minimo = $valor; 
	}

	function set_marca($valor){
		$this->marca = $valor; 
	}

	function set_modelo($valor){
		$this->modelo = $valor; 
	}

	function set_tipo_unidad($valor){
		$this->tipo_unidad = $valor; 
	}

	function set_apellido($valor){
		$this->apellido = $valor; 
	}

	function set_cedula($valor){
		$this->cedula = $valor; 
	}

	function set_nombre_usuario($valor){
		$this->nombre_usuario = $valor; 
	}

	function set_tipo_usuario($valor){
		$this->tipo_usuario = $valor; 
	}

	function set_fecha($valor){
		$this->fecha = $valor;
	}

	function set_cliente($valor){
		$this->cliente = $valor;
	}

	function set_proveedor($valor){
		$this->proveedor = $valor;
	}

	function set_fecha_inicio($valor){
		$this->fecha_inicio = $valor;
	}

	function set_fecha_fin($valor){
		$this->fecha_fin = $valor;
	}

	function set_precio($valor){
		$this->precio = $valor;
	}

	function set_presentacion($valor){
		$this->presentacion = $valor;
	}

	function reporte_usuarios(){	
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("Select * from usuarios where 
				cedula like :cedula and
				nombre like :nombre and
				apellido like :apellido and
				correo like :correo and
				telefono like :telefono and
				nombre_usuario like :nombre_usuario and
				tipo_usuario like :tipo_usuario
				");
			$resultado->bindValue(':cedula','%'.$this->cedula.'%');
			$resultado->bindValue(':nombre','%'.$this->nombre.'%');
			$resultado->bindValue(':apellido','%'.$this->apellido.'%');
			$resultado->bindValue(':correo','%'.$this->correo.'%');
			$resultado->bindValue(':telefono','%'.$this->telefono.'%');
			$resultado->bindValue(':nombre_usuario','%'.$this->nombre_usuario.'%');
			$resultado->bindValue(':tipo_usuario','%'.$this->tipo_usuario.'%');
			$resultado->execute();
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			$html = "<html><head></head><body>";
			$html = $html . "
			<div style='position: relative;'>
			<img src='otros/img/pdf/logo.jpg' style='width: 70px; position: absolute;  left: 650px;'>
			<h2 style='color: #14345a; text-align: center; margin: 0; padding-top: 5px;'>Centro Odontologico Vital Sonrisa, C.A<br>J-</h2>
			</div>";		
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Direccion <br><strong>Telefono:</strong> 0414-<br><strong>Fecha:</strong> ".$fecha.".</p>";	
			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Usuarios</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
			$html = $html."<th style='border: solid;'>#</th>";			
			$html = $html."<th style='border: solid;'>Cedula</th>";
			$html = $html."<th style='border: solid;'>Nombre</th>";
			$html = $html."<th style='border: solid;'>Apellido</th>";
			$html = $html."<th style='border: solid;'>Correo</th>";
			$html = $html."<th style='border: solid;'>Telefono</th>";
			$html = $html."<th style='border: solid;'>Nombre de usuario</th>";
			$html = $html."<th style='border: solid;'>Tipo de usuario</th>";									
			$html = $html."</tr>";
			$html = $html."</thead>";
			$html = $html."<tbody>";
			if($fila){
				$n=1;
				foreach($fila as $f){
					$html = $html."<tr>";
					$html = $html."<td style='text-align:center; border: solid; '>".$n."</td>";
					$html = $html."<td style='border: solid;'>".$f['cedula']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['apellido']."</td>";
					$html = $html."<td style='border: solid;'>".$f['correo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['telefono']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre_usuario']."</td>";
					$html = $html."<td style='border: solid;'>".$f['tipo_usuario']."</td>";								 
					$html = $html."</tr>";
					$n++;
				}
			}
			else{	
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
		$pdf->stream("ReporteUsuarios".$fecha.".pdf", array("Attachment" => false));	
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
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Direccion <br><strong>Telefono:</strong> 0414-<br><strong>Fecha:</strong> ".$fecha.".</p>";	



			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Insumos</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
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
					$html = $html."<td style='text-align:center; border: solid; '".$n."></td>";
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





function reporte_equipos(){    
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('d-m-y H:i');
			$resultado = $co->prepare("SELECT e.codigo, e.nombre, e.marca,e.modelo, e.cantidad as stock_total, e.precio FROM equipos WHERE e.codigo like :codigo and e.nombre like :nombre and e.marca like :marca and e.modelo like :modelo and e.cantidad like :stock_total and e.precio like :precio");
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
			$html = $html . "<p style='color: #14345a;'><strong>Direccion:</strong> Direccion <br><strong>Telefono:</strong> 0414-<br><strong>Fecha:</strong> ".$fecha.".</p>";	

			$html = $html . "<div style='background-color: #f1c40f; border: solid;' ><h2 style='color:#14345a; text-align: center;'>Reporte Equipos</h2></div>";
			$html = $html."<table style='width:100%; border: solid;' >";
			$html = $html."<thead style='width:100%;'>";
			$html = $html."<tr style='background-color: #f7dc6f ; '>";
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
					$html = $html."<td style='text-align:center; border: solid; '".$n."></td>";
					$html = $html."<td style='border: solid;'>".$f['codigo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['nombre']."</td>";
					$html = $html."<td style='border: solid;'>".$f['marca']."</td>";
					$html = $html."<td style='border: solid;'>".$f['modelo']."</td>";
					$html = $html."<td style='border: solid;'>".$f['cantidad']."</td>";
					$html = $html."<td style='border: solid;'>$".$f['precio']."</td>";
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