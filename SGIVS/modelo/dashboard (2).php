<?php
require_once('modelo/datos.php');
class dashboard extends datos {
    
    // Método para obtener datos de ingresos por mes
    function obtenerIngresosPorMes($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT 
                DATE_FORMAT(fecha, '%Y-%m') as mes,
                SUM(monto) as total
                FROM ingresos 
                $where
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
    function obtenerEgresosPorMes($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT 
                DATE_FORMAT(fecha, '%Y-%m') as mes,
                SUM(monto) as total
                FROM egresos 
                $where
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
    function obtenerTotalIngresos($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT SUM(monto) as total FROM ingresos $where");
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch(Exception $e) {
            error_log("Error en obtenerTotalIngresos: " . $e->getMessage());
            return 0;
        }
    }

    // Método para obtener total de egresos
    function obtenerTotalEgresos($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT SUM(monto) as total FROM egresos $where");
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch(Exception $e) {
            error_log("Error en obtenerTotalEgresos: " . $e->getMessage());
            return 0;
        }
    }

    // Método para obtener ingresos por origen
    function obtenerIngresosPorOrigen($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT 
                origen,
                SUM(monto) as total
                FROM ingresos 
                $where
                GROUP BY origen
                HAVING total > 0");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerIngresosPorOrigen: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener egresos por origen
    function obtenerEgresosPorOrigen($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT 
                origen,
                SUM(monto) as total
                FROM egresos 
                $where
                GROUP BY origen
                HAVING total > 0");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerEgresosPorOrigen: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener últimos ingresos
    function obtenerUltimosIngresos($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT 
                descripcion,
                monto,
                fecha,
                origen
                FROM ingresos 
                $where
                ORDER BY fecha DESC
                LIMIT 5");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerUltimosIngresos: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener últimos egresos
    function obtenerUltimosEgresos($cuenta = '', $fechaInicio = '', $fechaFin = '') {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $where = "WHERE monto > 0";
            if (!empty($cuenta)) {
                $where .= " AND cuenta_id = '$cuenta'";
            }
            if (!empty($fechaInicio)) {
                $where .= " AND fecha >= '$fechaInicio'";
            }
            if (!empty($fechaFin)) {
                $where .= " AND fecha <= '$fechaFin'";
            }

            $resultado = $co->query("SELECT 
                descripcion,
                monto,
                fecha,
                origen
                FROM egresos 
                $where
                ORDER BY fecha DESC
                LIMIT 5");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerUltimosEgresos: " . $e->getMessage());
            return array();
        }
    }

    // Método para obtener lista de cuentas
    function obtenerCuentas() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->query("SELECT id, nombre FROM cuentas WHERE activa = 1 ORDER BY nombre");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            error_log("Error en obtenerCuentas: " . $e->getMessage());
            return array();
        }
    }
}
?> 