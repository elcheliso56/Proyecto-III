<?php
require_once('../modelo/datos.php');
require_once('../vendor/autoload.php');

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteCuentas extends datos {
    private $tipo_reporte;
    private $fecha_inicio;
    private $fecha_fin;
    private $cuenta_id;
    private $moneda;
    private $estado;

    public function __construct($params) {
        $this->tipo_reporte = $params['tipo_reporte'] ?? '';
        $this->fecha_inicio = $params['fecha_inicio'] ?? '';
        $this->fecha_fin = $params['fecha_fin'] ?? '';
        $this->cuenta_id = $params['cuenta_id'] ?? '';
        $this->moneda = $params['moneda'] ?? '';
        $this->estado = $params['estado'] ?? '';
    }

    public function generarReporte() {
        $html = $this->generarHTML();
        
        // Configurar DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Generar nombre del archivo
        $filename = 'reporte_' . $this->tipo_reporte . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Enviar el PDF al navegador
        $dompdf->stream($filename, array('Attachment' => false));
    }

    private function generarHTML() {
        $datos = $this->obtenerDatos();
        $titulo = $this->obtenerTitulo();
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $titulo . '</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #333; }
                .header p { color: #666; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f5f5f5; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . $titulo . '</h1>
                <p>Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>
            </div>';

        // Agregar contenido según el tipo de reporte
        switch($this->tipo_reporte) {
            case 'estado_cuentas':
                $html .= $this->generarHTMLEstadoCuentas($datos);
                break;
            case 'movimientos':
                $html .= $this->generarHTMLMovimientos($datos);
                break;
            case 'cuentas_cobrar':
                $html .= $this->generarHTMLCuentasCobrar($datos);
                break;
            case 'cuentas_pagar':
                $html .= $this->generarHTMLCuentasPagar($datos);
                break;
        }

        $html .= '
            <div class="footer">
                <p>Este es un documento generado automáticamente por el sistema SGIVS</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    private function obtenerDatos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $where = array();
        $params = array();
        
        if ($this->cuenta_id) {
            $where[] = "c.id = :cuenta_id";
            $params[':cuenta_id'] = $this->cuenta_id;
        }
        
        if ($this->moneda) {
            $where[] = "c.moneda = :moneda";
            $params[':moneda'] = $this->moneda;
        }
        
        if ($this->estado !== '') {
            $where[] = "c.activa = :estado";
            $params[':estado'] = $this->estado;
        }
        
        if ($this->fecha_inicio && $this->fecha_fin) {
            $where[] = "DATE(c.fecha_creacion) BETWEEN :fecha_inicio AND :fecha_fin";
            $params[':fecha_inicio'] = $this->fecha_inicio;
            $params[':fecha_fin'] = $this->fecha_fin;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        switch($this->tipo_reporte) {
            case 'estado_cuentas':
                $sql = "SELECT c.*, 
                        COALESCE(SUM(i.monto), 0) as total_ingresos,
                        COALESCE(SUM(e.monto), 0) as total_egresos
                        FROM cuentas c
                        LEFT JOIN ingresos i ON c.id = i.cuenta_id
                        LEFT JOIN egresos e ON c.id = e.cuenta_id
                        $whereClause
                        GROUP BY c.id";
                break;
                
            case 'movimientos':
                $sql = "SELECT c.nombre as cuenta_nombre, 
                        i.fecha as fecha_ingreso, i.monto as monto_ingreso, i.descripcion as descripcion_ingreso,
                        e.fecha as fecha_egreso, e.monto as monto_egreso, e.descripcion as descripcion_egreso
                        FROM cuentas c
                        LEFT JOIN ingresos i ON c.id = i.cuenta_id
                        LEFT JOIN egresos e ON c.id = e.cuenta_id
                        $whereClause
                        ORDER BY COALESCE(i.fecha, e.fecha)";
                break;
                
            case 'cuentas_cobrar':
                $sql = "SELECT cxc.*, c.nombre as cuenta_nombre, p.nombre as paciente_nombre
                        FROM cuentas_por_cobrar cxc
                        JOIN cuentas c ON cxc.cuenta_id = c.id
                        JOIN pacientes p ON cxc.paciente_id = p.id_paciente
                        $whereClause
                        ORDER BY cxc.fecha_vencimiento";
                break;
                
            case 'cuentas_pagar':
                $sql = "SELECT cxp.*, c.nombre as cuenta_nombre, p.nombre as proveedor_nombre
                        FROM cuentas_por_pagar cxp
                        JOIN cuentas c ON cxp.cuenta_id = c.id
                        JOIN proveedores p ON cxp.proveedor_id = p.id
                        $whereClause
                        ORDER BY cxp.fecha_vencimiento";
                break;
        }
        
        $stmt = $co->prepare($sql);
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerTitulo() {
        switch($this->tipo_reporte) {
            case 'estado_cuentas':
                return 'Estado de Cuentas';
            case 'movimientos':
                return 'Reporte de Movimientos';
            case 'cuentas_cobrar':
                return 'Cuentas por Cobrar';
            case 'cuentas_pagar':
                return 'Cuentas por Pagar';
            default:
                return 'Reporte de Cuentas';
        }
    }

    private function generarHTMLEstadoCuentas($datos) {
        $html = '<table>
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Tipo</th>
                    <th>Moneda</th>
                    <th>Saldo Actual</th>
                    <th>Total Ingresos</th>
                    <th>Total Egresos</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach($datos as $row) {
            $html .= '<tr>
                <td>' . htmlspecialchars($row['nombre']) . '</td>
                <td>' . htmlspecialchars($row['tipo']) . '</td>
                <td>' . htmlspecialchars($row['moneda']) . '</td>
                <td>' . number_format($row['saldo_actual'], 2) . '</td>
                <td>' . number_format($row['total_ingresos'], 2) . '</td>
                <td>' . number_format($row['total_egresos'], 2) . '</td>
                <td>' . ($row['activa'] ? 'Activa' : 'Inactiva') . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }

    private function generarHTMLMovimientos($datos) {
        $html = '<table>
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach($datos as $row) {
            if ($row['fecha_ingreso']) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($row['cuenta_nombre']) . '</td>
                    <td>' . date('d/m/Y', strtotime($row['fecha_ingreso'])) . '</td>
                    <td>Ingreso</td>
                    <td>' . number_format($row['monto_ingreso'], 2) . '</td>
                    <td>' . htmlspecialchars($row['descripcion_ingreso']) . '</td>
                </tr>';
            }
            if ($row['fecha_egreso']) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($row['cuenta_nombre']) . '</td>
                    <td>' . date('d/m/Y', strtotime($row['fecha_egreso'])) . '</td>
                    <td>Egreso</td>
                    <td>' . number_format($row['monto_egreso'], 2) . '</td>
                    <td>' . htmlspecialchars($row['descripcion_egreso']) . '</td>
                </tr>';
            }
        }
        
        $html .= '</tbody></table>';
        return $html;
    }

    private function generarHTMLCuentasCobrar($datos) {
        $html = '<table>
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Paciente</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>Monto Total</th>
                    <th>Monto Pendiente</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach($datos as $row) {
            $html .= '<tr>
                <td>' . htmlspecialchars($row['cuenta_nombre']) . '</td>
                <td>' . htmlspecialchars($row['paciente_nombre']) . '</td>
                <td>' . date('d/m/Y', strtotime($row['fecha_emision'])) . '</td>
                <td>' . date('d/m/Y', strtotime($row['fecha_vencimiento'])) . '</td>
                <td>' . number_format($row['monto_total'], 2) . '</td>
                <td>' . number_format($row['monto_pendiente'], 2) . '</td>
                <td>' . htmlspecialchars($row['estado']) . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }

    private function generarHTMLCuentasPagar($datos) {
        $html = '<table>
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Proveedor</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>Monto Total</th>
                    <th>Monto Pendiente</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach($datos as $row) {
            $html .= '<tr>
                <td>' . htmlspecialchars($row['cuenta_nombre']) . '</td>
                <td>' . htmlspecialchars($row['proveedor_nombre']) . '</td>
                <td>' . date('d/m/Y', strtotime($row['fecha_emision'])) . '</td>
                <td>' . date('d/m/Y', strtotime($row['fecha_vencimiento'])) . '</td>
                <td>' . number_format($row['monto_total'], 2) . '</td>
                <td>' . number_format($row['monto_pendiente'], 2) . '</td>
                <td>' . htmlspecialchars($row['estado']) . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }
}

// Procesar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporte = new ReporteCuentas($_POST);
    $reporte->generarReporte();
}
?> 