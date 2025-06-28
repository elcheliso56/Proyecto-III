<?php 
require_once("comunes/encabezado.php"); // Incluye el encabezado de la página
require_once('comunes/menu.php'); // Incluye el menú de navegación
require_once('modelo/principal.php'); // Incluye el modelo principal para obtener datos
$conteos = new principal(); // Crea una instancia de la clase principal
$datos = $conteos->obtenerConteos(); // Obtiene los conteos de diferentes categorías
?>


<div class="container-fluid px-4">
    <!-- Encabezado con bienvenida -->
    <div class="d-flex justify-content-between align-items-center mb-4 py-3 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-speedometer2 me-2"></i> Panel Principal</h1>
        </div>
        <div class="d-flex align-items-center">
            <img src="<?php echo isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'otros/img/menu/avatar.jpg'; ?>" 
            alt="Avatar" class="rounded-circle me-2" width="40" height="40">
            <span class="text-dark">¡Bienvenido, <?php echo ucfirst($_SESSION['nombre_apellido']); ?>!</span>
        </div>
    </div>

    <!-- Sección de estadísticas -->
    <div class="row justify-content-center">
        <!-- Tarjeta de Empleados -->
        <?php if (tienePermiso('5')): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Empleados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['empleados']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=empleados" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Tarjeta de Historiales -->
        <?php if (tienePermiso('6')): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Historiales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['pacientes']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard2-pulse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=historial" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Tarjeta de Publicidad -->
        <?php if (tienePermiso('2')): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Citas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['citas']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tags-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=citas" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (tienePermiso('7')): ?>
        <!-- Tarjeta de Proveedores -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Servicios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['servicios']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=servicios" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (tienePermiso('11')): ?>
        <!-- Tarjeta de Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                               Insumos </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['servicios_insumos']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=gestionarInsumos" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (tienePermiso('9')): ?>
        <!-- Tarjeta de Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Equipos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['servicios_equipos']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=gestionarEquipos" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (tienePermiso('13')): ?>
        <!-- Tarjeta de Apartados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Cuentas por cobrar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['pagos_cuentas_por_cobrar']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bag-check-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=cxc" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (tienePermiso('18')): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['usuario']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-lock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=usuario" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>


</div>
</div>
</section>
</section>
</body>
</html>