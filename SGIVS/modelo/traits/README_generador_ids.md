# Generador de IDs Únicos

Este trait proporciona funcionalidad para generar IDs únicos que pueden ser reutilizados en cualquier modelo del sistema.

## Uso Básico

### 1. Incluir el trait en tu modelo

```php
<?php
require_once('modelo/datos.php');
require_once('modelo/traits/generador_ids.php');

class tu_modelo extends datos {
    use generador_ids;
    
    // ... resto de tu código
}
```

### 2. Generar ID único simple

```php
// En tu método incluir()
function incluir() {
    // Generar ID único con formato: PREFIJO + timestamp + 3 dígitos aleatorios
    $this->id = $this->generarIdUnico('nombre_tabla', 'PREFIJO');
    
    // Ejemplo para pacientes
    $this->id = $this->generarIdUnico('pacientes', 'PAC');
    // Resultado: PAC1704067200123
    
    // Ejemplo para empleados
    $this->id = $this->generarIdUnico('empleados', 'EMP');
    // Resultado: EMP1704067200456
}
```

### 3. Generar ID con formato personalizado

```php
// Formato con fecha en lugar de timestamp
$id = $this->generarIdUnicoFormato('pacientes', 'PAC{date}{random}');
// Resultado: PAC20240101123

// Formato con año y mes
$id = $this->generarIdUnicoFormato('facturas', 'FAC{year}{month}{random}', ['longitud_random' => 4]);
// Resultado: FAC2024011234

// Formato mixto
$id = $this->generarIdUnicoFormato('citas', 'CIT{date}{timestamp}{random}');
// Resultado: CIT202401011704067200789
```

## Formatos Disponibles

- `{timestamp}` - Timestamp actual (segundos desde epoch)
- `{date}` - Fecha actual en formato Ymd (20240101)
- `{year}` - Año actual (2024)
- `{month}` - Mes actual con ceros a la izquierda (01-12)
- `{random}` - Dígitos aleatorios (longitud configurable)

## Ejemplos por Módulo

### Cuentas
```php
$this->id = $this->generarIdUnico('cuentas', 'CTA');
// Resultado: CTA1704067200123
```

### Ingresos
```php
$this->id = $this->generarIdUnico('ingresos', 'ING');
// Resultado: ING1704067200456
```

### Egresos
```php
$this->id = $this->generarIdUnico('egresos', 'EGR');
// Resultado: EGR1704067200789
```

### Cuentas por Cobrar
```php
$this->id = $this->generarIdUnico('cuentas_por_cobrar', 'COB');
// Resultado: COB1704067200123
```

### Cuotas de Pago
```php
$this->id = $this->generarIdUnico('cuotas_pago', 'CUA');
// Resultado: CUA1704067200456
```

### Bancos
```php
$this->id = $this->generarIdUnico('bancos', 'BAN');
// Resultado: BAN1704067200123
```

### Pacientes
```php
$this->id = $this->generarIdUnico('pacientes', 'PAC');
// Resultado: PAC1704067200123
```

### Empleados
```php
$this->id = $this->generarIdUnico('empleados', 'EMP');
// Resultado: EMP1704067200456
```

### Facturas
```php
$this->id = $this->generarIdUnicoFormato('facturas', 'FAC{date}{random}', ['longitud_random' => 4]);
// Resultado: FAC202401011234
```

### Citas
```php
$this->id = $this->generarIdUnicoFormato('citas', 'CIT{date}{time}{random}', ['longitud_random' => 2]);
// Resultado: CIT20240101143012
```

### Productos
```php
$this->id = $this->generarIdUnico('productos', 'PRO');
// Resultado: PRO1704067200789
```

## Características

- **Único**: Verificado automáticamente en la base de datos
- **Legible**: Prefijo identifica el tipo
- **Ordenable**: Los más nuevos aparecen al final
- **Seguro**: Usa prepared statements
- **Eficiente**: Cierra conexiones automáticamente
- **Reutilizable**: Funciona para cualquier tabla

## Notas Importantes

1. El trait debe ser usado en clases que extiendan de `datos`
2. La tabla debe tener un campo `id` de tipo `varchar`
3. El método verifica automáticamente la unicidad del ID
4. Si se genera un ID duplicado (muy improbable), se genera uno nuevo automáticamente 