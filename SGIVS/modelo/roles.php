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
                // Asegurarse que el estado sea ACTIVO al incluir
                $this->estado = 'ACTIVO';
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
			// Actualiza los datos del empleado en la base de datos				
			$co->query("Update roles set 
				nombre_rol = '$this->nombre_rol',
				descripcion = '$this->descripcion',
				estado = '$this->estado'
				where
				id = '$this->id'
				");
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  '¡Registro modificado con éxito!';
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
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

        // Primero verificar si el rol existe
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            // Verificar si el rol existe
            $stmt = $co->prepare("SELECT id FROM roles WHERE id = ?");
            $stmt->execute([$this->id]);
            if (!$stmt->fetch()) {
                return array(
                    'resultado' => 'error',
                    'mensaje' => 'El rol especificado no existe'
                );
            }

            $co->beginTransaction();
            
            // Eliminar permisos actuales
            $stmt_delete = $co->prepare("DELETE FROM rol_permiso WHERE id_rol = ?");
            $stmt_delete->execute([$this->id]);
            
            // Insertar nuevos permisos
            if(!empty($permisos_seleccionados)){
                $stmt_insert = $co->prepare("INSERT INTO rol_permiso (id_rol, id_permiso) VALUES (?, ?)");
                foreach($permisos_seleccionados as $id_permiso){
                    if(!empty($id_permiso)) {
                        $stmt_insert->execute([$this->id, $id_permiso]);
                    }
                }
            }
            
            $co->commit();
            
            // Verificar cuántos permisos se guardaron
            $stmt_count = $co->prepare("SELECT COUNT(*) as total FROM rol_permiso WHERE id_rol = ?");
            $stmt_count->execute([$this->id]);
            $count = $stmt_count->fetch(PDO::FETCH_ASSOC);
            
            return array(
                'resultado' => 'permisos',
                'mensaje' => 'Permisos actualizados exitosamente. Total de permisos guardados: ' . $count['total'],
                'total_permisos' => $count['total']
            );
        } catch(Exception $e) {
            if ($co->inTransaction()) {
                $co->rollBack();
            }
            return array(
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            );
        } finally {
            $co = null;
        }
    }

    function cambiar_estado(){
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Verificar si es el rol de administrador
            $resultado = $co->query("SELECT nombre_rol FROM roles WHERE id = '$this->id'");
            $rol = $resultado->fetch(PDO::FETCH_ASSOC);
            
            if($rol['nombre_rol'] === 'ADMINISTRADOR') {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede modificar el estado del rol ADMINISTRADOR';
                return $r;
            }

            $co->query("UPDATE roles SET estado = '$this->estado' WHERE id = '$this->id'");
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = '¡Estado actualizado con éxito!';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        $co = null;
        return $r;
    }
}
?>