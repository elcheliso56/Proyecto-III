<?php
require_once('modelo/datos.php');
class dashboard extends datos {
    
    // Método para obtener datos de ingresos por mes
    function obtenerIngresosPorMes() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT 
                DATE_FORMAT(fecha, '%Y-%m') as mes,
                SUM(monto) as total
                FROM ingresos 
                GROUP BY DATE_FORMAT(fecha, '%Y-%m')
                ORDER BY mes DESC
                LIMIT 12");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerIngresosPorMes: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener datos de egresos por mes
    function obtenerEgresosPorMes() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT 
                DATE_FORMAT(fecha, '%Y-%m') as mes,
                SUM(monto) as total
                FROM egresos 
                WHERE fecha IS NOT NULL 
                AND monto > 0
                GROUP BY DATE_FORMAT(fecha, '%Y-%m')
                ORDER BY mes DESC
                LIMIT 12");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerEgresosPorMes: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener total de ingresos
    function obtenerTotalIngresos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT SUM(monto) as total FROM ingresos WHERE monto > 0");
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch(Exception $e) {
            error_log("Error en obtenerTotalIngresos: " . $e->getMessage());
            return 0;
        }
    }

    // Método para obtener total de egresos
    function obtenerTotalEgresos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT SUM(monto) as total FROM egresos WHERE monto > 0");
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch(Exception $e) {
            error_log("Error en obtenerTotalEgresos: " . $e->getMessage());
            return 0;
        }
    }

    // Método para obtener ingresos por origen
    function obtenerIngresosPorOrigen() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT 
                origen,
                SUM(monto) as total
                FROM ingresos 
                WHERE monto > 0
                GROUP BY origen
                HAVING total > 0");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerIngresosPorOrigen: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener egresos por origen
    function obtenerEgresosPorOrigen() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT 
                origen,
                SUM(monto) as total
                FROM egresos 
                WHERE monto > 0
                GROUP BY origen
                HAVING total > 0");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerEgresosPorOrigen: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener últimos ingresos
    function obtenerUltimosIngresos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT 
                descripcion,
                monto,
                fecha,
                origen
                FROM ingresos 
                WHERE monto > 0
                ORDER BY fecha DESC
                LIMIT 5");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerUltimosIngresos: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener últimos egresos
    function obtenerUltimosEgresos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT 
                descripcion,
                monto,
                fecha,
                origen
                FROM egresos 
                WHERE monto > 0
                ORDER BY fecha DESC
                LIMIT 5");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerUltimosEgresos: " . $e->getMessage());
            return array();
        }
    }
}
?> 