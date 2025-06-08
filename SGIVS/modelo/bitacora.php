<?php
require_once('modelo/datos.php');

class bitacora extends datos {
    private $fecha_hora;
    private $modulo;
    private $accion;
    private $descripcion;
    private $detalles;
    private $usuario_id;

    // Métodos para establecer valores
    function set_fecha_hora($valor) {
        $this->fecha_hora = $valor;
    }

    function set_modulo($valor) {
        $this->modulo = $valor;
    }

    function set_accion($valor) {
        $this->accion = $valor;
    }

    function set_descripcion($valor) {
        $this->descripcion = $valor;
    }

    function set_detalles($valor) {
        $this->detalles = $valor;
    }

    function set_usuario_id($valor) {
        $this->usuario_id = $valor;
    }

    // Método para consultar la bitácora
    function consultar() {
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT b.*, u.usuario, u.nombre_apellido 
                                   FROM bitacora b 
                                   LEFT JOIN usuario u ON b.usuario_id = u.usuario 
                                   ORDER BY b.fecha_hora DESC");
            if ($resultado) {
                $respuesta = '';
                $n = 1;
                foreach ($resultado as $r) {
                    $fecha_hora = new DateTime($r['fecha_hora']);
                    $respuesta = $respuesta . "<tr class='text-center'>";
                    $respuesta = $respuesta . "<td class='align-middle'>$n</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $fecha_hora->format('d-m-Y') . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $fecha_hora->format('H:i:s') . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['usuario'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['nombre_apellido'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['accion'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['descripcion'] . "</td>";
                    $respuesta = $respuesta . "<td class='align-middle'>" . $r['modulo'] . "</td>";
                    $respuesta = $respuesta . "</tr>";
                    $n++;
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

    // Método para generar reporte PDF
    function generar_reporte() {
        $co = $this->conecta_usuarios();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT b.*, u.usuario, u.nombre_apellido 
                                   FROM bitacora b 
                                   LEFT JOIN usuario u ON b.usuario_id = u.usuario 
                                   ORDER BY b.fecha_hora DESC");
            
            $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
            $html = $html . "<title>Reporte de Bitácora</title>";
            $html = $html . "<style>
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid black; padding: 5px; }
                th { background-color: #f7dc6f; }
                h2 { color: #14345a; text-align: center; }
                .header { background-color: #f1c40f; border: solid; }
            </style></head><body>";
            
            $html = $html . "<div class='header'><h2>Reporte de Bitácora del Sistema</h2></div>";
            $html = $html . "<table>";
            $html = $html . "<thead>";
            $html = $html . "<tr>";
            $html = $html . "<th>#</th>";
            $html = $html . "<th>Fecha</th>";
            $html = $html . "<th>Hora</th>";
            $html = $html . "<th>Usuario</th>";
            $html = $html . "<th>Acción</th>";
            $html = $html . "<th>Descripción</th>";
            $html = $html . "<th>Módulo</th>";
            $html = $html . "</tr></thead><tbody>";
            
            if ($resultado) {
                $n = 1;
                foreach ($resultado as $fila) {
                    $fecha_hora = new DateTime($fila['fecha_hora']);
                    $html = $html . "<tr>";
                    $html = $html . "<td style='text-align:center'>" . $n . "</td>";
                    $html = $html . "<td>" . $fecha_hora->format('d-m-Y') . "</td>";
                    $html = $html . "<td>" . $fecha_hora->format('H:i:s') . "</td>";
                    $html = $html . "<td>" . $fila['usuario'] . "</td>";
                    $html = $html . "<td>" . $fila['nombre_apellido'] . "</td>";
                    $html = $html . "<td>" . $fila['accion'] . "</td>";
                    $html = $html . "<td>" . $fila['descripcion'] . "</td>";
                    $html = $html . "<td>" . $fila['modulo'] . "</td>";
                    $html = $html . "</tr>";
                    $n++;
                }
            }
            
            $html = $html . "</tbody></table></body></html>";
            
            $r['resultado'] = 'reporte';
            $r['mensaje'] = $html;
            
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?> 