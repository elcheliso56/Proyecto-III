<?php
require_once('modelo/datos.php');
class roles extends datos{
    
	private $id;
	private $nombre_rol;
	private $descripcion;
	private $estado;
	private $id_original;
	private $nombre_rol_original;
    
	function set_id($valor){
		$this->id = $valor;
	}	
	function set_nombre_rol($valor){
		$this->nombre_rol = $valor;
	}	
	function set_descripcion($valor){
		$this->descripcion = $valor;
	}	
	function set_estado($valor){
		$this->estado = $valor;
	}	
	function set_id_original($valor){
		$this->id_original = $valor;
	}	
	function set_nombre_rol_original($valor){
		$this->nombre_rol_original = $valor;
	}
    
	function get_id(){
		return $this->id;
	}
	function get_nombre_rol(){
		return $this->nombre_rol;
	}
	function get_descripcion(){
		return $this->descripcion;
	}
	function get_estado(){
		return $this->estado;
	}	
    
	function incluir(){
		$r = array();
        // Verifica si el número de id ya existe		
		if(!$this->existe($this->nombre_rol)){
			$co = $this->conecta_usuarios();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo empleado en la base de datos			
				$co->query("Insert into roles(id,nombre_rol,descripcion,estado)
					Values(
					'$this->id',
					'$this->nombre_rol',
					'$this->descripcion',
					'$this->estado'
					)");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el rol';
		}
		$co = null;
		return $r;
	}

    // Método para modificar un empleado existente	
	function modificar(){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$co->beginTransaction();

			// Verificar si el nuevo nombre_rol ya existe
			$stmt = $co->prepare("SELECT COUNT(*) FROM roles WHERE nombre_rol = ? AND id != ?");
			$stmt->execute([$this->nombre_rol, $this->id]);
			if($stmt->fetchColumn() > 0) {
				throw new Exception("El nombre de rol ya existe en la base de datos");
			}

			// Actualizar la tabla roles
			$stmt = $co->prepare("UPDATE roles SET 
				nombre_rol = ?,
				descripcion = ?,
				estado = ?
				WHERE id = ?");
			
			$stmt->execute([
				$this->nombre_rol,
				$this->descripcion,
				$this->estado,
				$this->id
			]);

			if($stmt->rowCount() > 0) {
				$co->commit();
				$r['resultado'] = 'modificar';
				$r['mensaje'] = '¡Registro modificado con éxito!';
			} else {
				throw new Exception("No se encontró el rol a modificar");
			}
		} catch(Exception $e) {
			$co->rollBack();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		$co = null;
		return $r;
	}

    // Método para eliminar un empleado	
	function eliminar(){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
         // Verifica si el número de documento existe		
		if($this->existe($this->nombre_rol)){
			try {
				// Elimina el empleado de la base de datos
				$co->query("delete from roles where	nombre_rol = '$this->nombre_rol'");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este rol porque tiene usuarios asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el rol';
		}
		$co = null;
		return $r;
	}	

    // Método para consultar todos los empleado	
	function consultar(){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("SELECT * FROM roles ORDER BY id ASC");
			$roles = [];
			foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
				$roles[] = $row;
			}
			$r['resultado'] = 'consultar';
			$r['mensaje'] = $roles;
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		$co = null;
		return $r;
	}
	// Método privado para verificar si un número de documento ya existe
	private function existe($nombre_rol){
		$co = $this->conecta_usuarios();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("Select * from roles where nombre_rol='$nombre_rol'");
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
				return true;  
			}
			else{	
				return false;
			}	
		}catch(Exception $e){
			return false;
		}
		$co = null;
	}	

    function consultar_permisos(){
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("SELECT * FROM permisos ORDER BY id_permiso ASC");
            $permisos = [];
            foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
                $permisos[] = $row;
            }
            $r['resultado'] = 'consultar_permisos';
            $r['mensaje'] = $permisos;
        }catch(Exception $e){
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        $co = null;
        return $r;
    }

    function consultar_permisos_rol(){
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $resultado = $co->query("SELECT p.id_permiso, p.nombre_permiso, 
                                   CASE WHEN rp.id_rol IS NOT NULL THEN 1 ELSE 0 END as tiene_permiso
                                   FROM permisos p
                                   LEFT JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso 
                                   AND rp.id_rol = '$this->id'
                                   ORDER BY p.id_permiso ASC");
            $permisos = [];
            foreach($resultado->fetchAll(PDO::FETCH_ASSOC) as $row){
                $permisos[] = $row;
            }
            $r['resultado'] = 'consultar_permisos_rol';
            $r['mensaje'] = $permisos;
        }catch(Exception $e){
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        $co = null;
        return $r;
    }

    function guardar_permisos($permisos_seleccionados){
        if (!is_array($permisos_seleccionados)) {
            return array(
                'resultado' => 'error',
                'mensaje' => 'Formato de permisos inválido'
            );
        }

        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try{
            $co->beginTransaction();
            
            // Eliminar permisos actuales
            $stmt_delete = $co->prepare("DELETE FROM rol_permiso WHERE id_rol = ?");
            $stmt_delete->execute([$this->id]);
            
            // Insertar nuevos permisos
            if(!empty($permisos_seleccionados)){
                $stmt_insert = $co->prepare("INSERT INTO rol_permiso (id_rol, id_permiso) VALUES (?, ?)");
                foreach($permisos_seleccionados as $id_permiso){
                    if(!empty($id_permiso)) { // Verificar que el ID no esté vacío
                        $stmt_insert->execute([$this->id, $id_permiso]);
                    }
                }
            }
            
            $co->commit();
            
            // Verificar cuántos permisos se guardaron
            $stmt_count = $co->prepare("SELECT COUNT(*) as total FROM rol_permiso WHERE id_rol = ?");
            $stmt_count->execute([$this->id]);
            $count = $stmt_count->fetch(PDO::FETCH_ASSOC);
            
            $r['resultado'] = 'permisos';
            $r['mensaje'] = 'Permisos actualizados exitosamente. Total de permisos guardados: ' . $count['total'];
            $r['total_permisos'] = $count['total'];
        }catch(Exception $e){
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        $co = null;
        return $r;
    }
}
?>