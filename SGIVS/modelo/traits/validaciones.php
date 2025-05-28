<?php
trait validaciones {
    private function validarDescripcion($descripcion) {
        if (empty($descripcion)) {
            return ['valido' => false, 'mensaje' => 'La descripción no puede estar vacía'];
        }
        if (!preg_match('/^[A-Za-zÀ-ÿ\s]{3,90}$/', $descripcion)) {
            return ['valido' => false, 'mensaje' => 'La descripción debe contener solo letras entre 3 y 90 caracteres'];
        }
        return ['valido' => true];
    }

    private function validarMonto($monto) {
        if (empty($monto)) {
            return ['valido' => false, 'mensaje' => 'El monto no puede estar vacío'];
        }
        if (!is_numeric($monto) || $monto <= 0) {
            return ['valido' => false, 'mensaje' => 'El monto debe ser un número positivo'];
        }
        if (strlen($monto) > 9) {
            return ['valido' => false, 'mensaje' => 'El monto no puede tener más de 9 dígitos'];
        }
        return ['valido' => true];
    }

    private function validarFecha($fecha) {
        if (empty($fecha)) {
            return ['valido' => false, 'mensaje' => 'La fecha no puede estar vacía'];
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return ['valido' => false, 'mensaje' => 'El formato de fecha no es válido'];
        }
        
        $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
        if (!$fecha_obj || $fecha_obj->format('Y-m-d') !== $fecha) {
            return ['valido' => false, 'mensaje' => 'La fecha no es válida'];
        }

        // Obtener la fecha actual
        $fecha_actual = new DateTime();
        $fecha_actual->setTime(0, 0, 0); // Establecer la hora a 00:00:00
        
        // Comparar las fechas
        if ($fecha_obj > $fecha_actual) {
            return ['valido' => false, 'mensaje' => 'No se pueden registrar ingresos/egresos con fechas futuras'];
        }

        return ['valido' => true];
    }

    private function validarOrigen($origen) {
        if (empty($origen)) {
            return ['valido' => false, 'mensaje' => 'El origen no puede estar vacío'];
        }
        
        // Convertir a minúsculas para hacer la comparación insensible a mayúsculas
        $origen = strtolower(trim($origen));
        
        // Lista de orígenes válidos
        $origenes_validos = [
            'servicio',
            'proveedor',
            'manual',
            'consulta',
            'otro'
        ];
        
        if (!in_array($origen, $origenes_validos)) {
            return [
                'valido' => false, 
                'mensaje' => 'El origen seleccionado no es válido. Valores permitidos: ' . implode(', ', $origenes_validos)
            ];
        }
        return ['valido' => true];
    }

    public function validarDatos() {
        $validaciones = [
            'descripcion' => $this->validarDescripcion($this->descripcion),
            'monto' => $this->validarMonto($this->monto),
            'fecha' => $this->validarFecha($this->fecha),
            'origen' => $this->validarOrigen($this->origen)
        ];

        foreach ($validaciones as $campo => $resultado) {
            if (!$resultado['valido']) {
                return [
                    'valido' => false,
                    'mensaje' => $resultado['mensaje']
                ];
            }
        }

        return ['valido' => true];
    }
} 