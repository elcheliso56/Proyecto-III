<?php 
require_once("comunes/encabezado.php"); // Incluye el encabezado de la página
require_once('comunes/menu.php'); // Incluye el menú de navegación
require_once('modelo/principal.php'); // Incluye el modelo principal para obtener datos
$conteos = new principal(); // Crea una instancia de la clase principal
$datos = $conteos->obtenerConteos(); // Obtiene los conteos de diferentes categorías
?>

<<<<<<< HEAD
<div class="container"> <!-- Contenedor principal -->
	<div class="container"> <!-- Contenedor secundario -->
		<h1 class="text-center full-width">Principal</h1> <!-- Título de la página -->
		<figure class="full-width navLateral-body-tittle-menu"> <!-- Figura para el avatar y saludo -->
			<div>
				<img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>" alt="Avatar" class="img-responsive" > <!-- Imagen del usuario -->
				<div><span class>¡Bienvenido <?php echo ucfirst($_SESSION['nombre_usuario']); ?>!</span></div> <!-- Saludo al usuario -->
			</figure>		
		</div>		
	</figure>
	<section class="full-width text-center"> <!-- Sección para los tiles de conteo -->
		<article class="full-width tile" > <!-- Tile para categorías -->
			<a href="?pagina=categorias" class="full-width">				
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['categorias']; ?><br> <!-- Muestra el conteo de categorías -->
						<small>Categorías</small> <!-- Etiqueta de categorías -->
					</span>
				</div>
				<i class="bi bi-tags-fill tile-icon"></i> <!-- Icono de categorías -->
			</a>	
		</article>
		<!-- Repite el mismo patrón para ubicaciones, proveedores, productos, clientes -->
		<article class="full-width tile">
			<a href="?pagina=ubicaciones" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['ubicaciones']; ?><br> <!-- Muestra el conteo de ubicaciones -->
						<small>Ubicaciones</small> <!-- Etiqueta de ubicaciones -->
					</span>
				</div>
				<i class="bi bi-geo-alt-fill tile-icon"></i> <!-- Icono de ubicaciones -->
			</a>
		</article>
		<article class="full-width tile">
			<a href="?pagina=proveedores" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['proveedores']; ?><br>
						<small>Proveedores</small>
					</span>
				</div>
				<i class="zmdi zmdi-truck tile-icon"></i>
			</a>
		</article>
		<article class="full-width tile">
			<a href="?pagina=productos" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['productos']; ?><br>
						<small>Productos</small>
					</span>
				</div>
				<i class="bi bi-box-seam-fill tile-icon"></i>
			</a>
		</article>
		<article class="full-width tile">
			<a href="?pagina=clientes" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['clientes']; ?><br>
						<small>Clientes</small>
					</span>
				</div>
				<i class="zmdi zmdi-accounts tile-icon"></i>
			</a>
		</article>
		<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
			<article class="full-width tile">
				<a href="?pagina=usuarios" class="full-width">
					<div class="tile-text">
						<span class="text-condensedLight">
							<?php echo $datos['usuarios']; ?><br> <!-- Muestra el conteo de usuarios -->
							<small>Usuarios</small> <!-- Etiqueta de usuarios -->
						</span>
					</div>
					<i class="zmdi zmdi-account tile-icon"></i> <!-- Icono de usuarios -->
				</a>
			</article>				
		<?php endif; ?>
		<article class="full-width tile"> <!-- Tile para apartados -->
			<a href="?pagina=apartados" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['apartados']; ?><br> <!-- Muestra el conteo de apartados -->
						<small>Apartados</small> <!-- Etiqueta de apartados -->
					</span>
				</div>
				<i class="bi bi-bag-check-fill tile-icon"></i> <!-- Icono de apartados -->
			</a>
		</article>
	</section>
=======
<div class="container-fluid px-4">
    <!-- Encabezado con bienvenida -->
    <div class="d-flex justify-content-between align-items-center mb-4 py-3 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-speedometer2 me-2"></i>Panel Principal</h1>
        </div>
        <div class="d-flex align-items-center">
            <img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>" 
            alt="Avatar" class="rounded-circle me-2" width="40" height="40">
            <span class="text-dark">¡Bienvenido, <?php echo ucfirst($_SESSION['nombre_usuario']); ?>!</span>
        </div>
    </div>

    <!-- Sección de estadísticas -->
    <div class="row">
        <!-- Tarjeta de Empleados -->
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

        <!-- Tarjeta de Pacientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pacientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['pacientes']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-heart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=pacientes" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Historiales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Historiales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['historial']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard2-pulse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=ubicaciones" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Publicidad -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Promociones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['publicidad']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tags-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=promociones" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de tarjetas -->
    <div class="row">
        <!-- Tarjeta de Proveedores -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Proveedores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['proveedores']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=proveedores" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Productos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['productos']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=productos" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['clientes']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=clientes" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Apartados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Apartados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['apartados']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bag-check-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=apartados" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Usuarios (solo para administradores) -->
    <?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
    <div class="row justify-content-center">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $datos['usuarios']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-lock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="?pagina=usuarios" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Sección de gráficos (opcional) -->
    <div class="row mt-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actividad Reciente</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                            src="img/undraw_medical_care_movn.svg" alt="Actividad">
                    </div>
                    <p>Visualización de la actividad reciente en el sistema. Próximamente se incluirán gráficos interactivos.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recordatorios</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                            src="img/undraw_Calendar_re_ki49.svg" alt="Recordatorios">
                    </div>
                    <p>Próximamente: recordatorios de citas y actividades pendientes.</p>
                </div>
            </div>
        </div>
    </div>
>>>>>>> 97d63db9f7d40d7496c83526597527d86af8297c
</div>
</div>
</section>
</section>
</body>
</html>