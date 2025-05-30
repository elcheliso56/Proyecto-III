<?php
require_once('modelo/datos.php');

class reportes_cuentas extends datos {
    private $tipo_reporte;
    private $fecha_inicio;
    private $fecha_fin;
    private $cuenta_id;
    private $moneda;
    private $estado;

    // Getters y setters
    function set_tipo_reporte($valor) {
        $this->tipo_reporte = $valor;
    }
    function get_tipo_reporte() {
        return $this->tipo_reporte;
    }

    function set_fecha_inicio($valor) {
        $this->fecha_inicio = $valor;
    }
    function get_fecha_inicio() {
        return $this->fecha_inicio;
    }

    function set_fecha_fin($valor) {
        $this->fecha_fin = $valor;
    }
    function get_fecha_fin() {
        return $this->fecha_fin;
    }

    function set_cuenta_id($valor) {
        $this->cuenta_id = $valor;
    }
    function get_cuenta_id() {
        return $this->cuenta_id;
    }

    function set_moneda($valor) {
        $this->moneda = $valor;
    }
    function get_moneda() {
        return $this->moneda;
    }

    function set_estado($valor) {
        $this->estado = $valor;
    }
    function get_estado() {
        return $this->estado;
    }

    // Método para consultar cuentas
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT id, nombre, tipo, moneda, activa 
                                   FROM cuentas 
                                   WHERE activa = 1 
                                   ORDER BY nombre");
            $cuentas = array();
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $cuentas[] = $row;
            }
            $r['resultado'] = 'consultar';
            $r['mensaje'] = $cuentas;
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Método para generar el reporte
    function generarReporte() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            // Construir la consulta según el tipo de reporte
            $sql = $this->construirConsulta();
            $resultado = $co->query($sql);
            $datos = array();
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $datos[] = $row;
            }
            $r['resultado'] = 'generar_reporte';
            $r['mensaje'] = $datos;
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Método privado para construir la consulta según el tipo de reporte
    private function construirConsulta() {
        $sql = "";
        $where = array();

        // Agregar condiciones según los filtros
        if (!empty($this->cuenta_id)) {
            $where[] = "c.id = " . $this->cuenta_id;
        }
        if (!empty($this->moneda)) {
            $where[] = "c.moneda = '" . $this->moneda . "'";
        }
        if (!empty($this->estado)) {
            $where[] = "c.activa = " . ($this->estado == 'activa' ? 1 : 0);
        }
        if (!empty($this->fecha_inicio)) {
            $where[] = "fecha >= '" . $this->fecha_inicio . "'";
        }
        if (!empty($this->fecha_fin)) {
            $where[] = "fecha <= '" . $this->fecha_fin . "'";
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        switch ($this->tipo_reporte) {
            case 'estado_cuentas':
                $sql = "SELECT c.nombre, c.tipo, c.moneda, c.saldo_actual,
                       COALESCE(SUM(i.monto), 0) as total_ingresos,
                       COALESCE(SUM(e.monto), 0) as total_egresos
                       FROM cuentas c
                       LEFT JOIN ingresos i ON c.id = i.cuenta_id
                       LEFT JOIN egresos e ON c.id = e.cuenta_id
                       $whereClause
                       GROUP BY c.id, c.nombre, c.tipo, c.moneda, c.saldo_actual";
                break;

            case 'movimientos':
                $sql = "(SELECT 'Ingreso' as tipo, i.fecha, i.descripcion, i.monto, c.nombre as cuenta
                        FROM ingresos i
                        JOIN cuentas c ON i.cuenta_id = c.id
                        $whereClause)
                        UNION ALL
                        (SELECT 'Egreso' as tipo, e.fecha, e.descripcion, e.monto, c.nombre as cuenta
                        FROM egresos e
                        JOIN cuentas c ON e.cuenta_id = c.id
                        $whereClause)
                        ORDER BY fecha DESC";
                break;

            case 'cuentas_cobrar':
                $sql = "SELECT cxc.id, p.nombre as paciente, cxc.fecha_emision, cxc.fecha_vencimiento,
                       cxc.monto_total, cxc.monto_pendiente, cxc.estado, cxc.descripcion
                       FROM cuentas_por_cobrar cxc
                       JOIN pacientes p ON cxc.paciente_id = p.id_paciente
                       $whereClause
                       ORDER BY cxc.fecha_vencimiento";
                break;

            case 'cuentas_pagar':
                $sql = "SELECT cxp.id, p.nombre as proveedor, cxp.fecha_emision, cxp.fecha_vencimiento,
                       cxp.monto_total, cxp.monto_pendiente, cxp.estado, cxp.descripcion
                       FROM cuentas_por_pagar cxp
                       JOIN proveedores p ON cxp.proveedor_id = p.id
                       $whereClause
                       ORDER BY cxp.fecha_vencimiento";
                break;
        }

        return $sql;
    }
}
?> 