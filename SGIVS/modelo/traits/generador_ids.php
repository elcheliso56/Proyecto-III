<?php
/**
 * Trait para generar IDs únicos
 * Puede ser usado en cualquier modelo que necesite generar IDs únicos
 */
trait generador_ids {
    
    /**
     * Genera un ID único para una tabla específica
     * @param string $tabla Nombre de la tabla
     * @param string $prefijo Prefijo para el ID (ej: 'CTA', 'PAC', 'EMP', etc.)
     * @param int $longitud_aleatoria Longitud de los dígitos aleatorios (por defecto 3)
     * @return string ID único generado
     */
    protected function generarIdUnico($tabla, $prefijo, $longitud_aleatoria = 3) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        do {
            // Generar ID con formato: PREFIJO + timestamp + dígitos aleatorios
            $timestamp = time();
            $random = str_pad(rand(0, pow(10, $longitud_aleatoria) - 1), $longitud_aleatoria, '0', STR_PAD_LEFT);
            $id = $prefijo . $timestamp . $random;
            
            // Verificar si el ID ya existe en la tabla especificada
            $stmt = $co->prepare("SELECT COUNT(*) FROM $tabla WHERE id = ?");
            $stmt->execute([$id]);
            $existe = $stmt->fetchColumn() > 0;
            
        } while ($existe);
        
        $co = null;
        return $id;
    }
    
    /**
     * Genera un ID único con formato personalizado
     * @param string $tabla Nombre de la tabla
     * @param string $formato Formato del ID (ej: 'CTA{timestamp}{random}', 'PAC{date}{random}')
     * @param array $opciones Opciones adicionales para el formato
     * @return string ID único generado
     */
    protected function generarIdUnicoFormato($tabla, $formato, $opciones = []) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        do {
            $id = $formato;
            
            // Reemplazar {timestamp} con timestamp actual
            if (strpos($id, '{timestamp}') !== false) {
                $id = str_replace('{timestamp}', time(), $id);
            }
            
            // Reemplazar {date} con fecha actual en formato Ymd
            if (strpos($id, '{date}') !== false) {
                $id = str_replace('{date}', date('Ymd'), $id);
            }
            
            // Reemplazar {random} con dígitos aleatorios
            if (strpos($id, '{random}') !== false) {
                $longitud = isset($opciones['longitud_random']) ? $opciones['longitud_random'] : 3;
                $random = str_pad(rand(0, pow(10, $longitud) - 1), $longitud, '0', STR_PAD_LEFT);
                $id = str_replace('{random}', $random, $id);
            }
            
            // Reemplazar {year} con año actual
            if (strpos($id, '{year}') !== false) {
                $id = str_replace('{year}', date('Y'), $id);
            }
            
            // Reemplazar {month} con mes actual
            if (strpos($id, '{month}') !== false) {
                $id = str_replace('{month}', date('m'), $id);
            }
            
            // Verificar si el ID ya existe en la tabla especificada
            $stmt = $co->prepare("SELECT COUNT(*) FROM $tabla WHERE id = ?");
            $stmt->execute([$id]);
            $existe = $stmt->fetchColumn() > 0;
            
        } while ($existe);
        
        $co = null;
        return $id;
    }
}
?> 