<?php
function tienePermiso($permiso) {
    return isset($_SESSION['permisos']) && is_array($_SESSION['permisos']) && in_array($permiso, $_SESSION['permisos']);
}
?>
<body>
	<!-- Área de notificaciones -->
	<section class="full-width container-notifications">
		<!-- Fondo de notificaciones -->
		<div class="full-width container-notifications-bg btn-Notification" ></div>
		<section class="NotificationArea" >
			<!-- Título de notificaciones -->
			<div class="full-width text-center NotificationArea-title tittles">Notificaciones <i class="zmdi zmdi-close btn-Notification"></i></div>
			<div id="notificacionesContenedor">
				<!-- Las notificaciones se cargarán aquí dinámicamente -->
			</div>
		</section>
	</section>
	<!-- Navegación lateral -->
	<section class="full-width navLateral">
		<!-- Fondo de la navegación lateral -->
		<div class="full-width navLateral-bg btn-menu"></div>
		<div class="full-width navLateral-body">
			<!-- Logo y título de la navegación lateral -->
			<div class="full-width navLateral-body-logo text-center tittles">
				<i class="zmdi zmdi-close btn-menu"></i> MENU 
			</div>
			<figure class="full-width navLateral-body-tittle-menu">
				<div>
					<!-- Imagen de usuario -->
					<img src="<?php echo isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'otros/img/menu/avatar.jpg'; ?>" alt="Avatar" class="img-responsive navLateral-body-img">
				</div>
				<figcaption>
					<span>
						<!-- Nombre y tipo de usuario -->
						<?php echo isset($_SESSION['nombre_apellido']) ? $_SESSION['nombre_apellido'] : ''; ?><br>
					</span>
				</figcaption>
			</figure>
			
			<nav class="full-width">
				<ul class="full-width list-unstyle menu-principal">
					<?php if (tienePermiso('1')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=principal" class="full-width">
								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-view-dashboard"></i>
								</div>
								<div class="navLateral-body-cr">
									PRINCIPAL
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('2')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=citas" class="full-width">
								<div class="navLateral-body-cl">
									<i class="bi bi-calendar-check"></i>
								</div>
								<div class="navLateral-body-cr">
									CITAS
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('3')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=calendario" class="full-width">
								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-calendar"></i>
								</div>
								<div class="navLateral-body-cr">
									CALENDARIO
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('4')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=consultas" class="full-width">
								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-view-dashboard"></i>
								</div>
								<div class="navLateral-body-cr">
									CONSULTAS
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('5')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=empleados" class="full-width">
								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-account"></i>
								</div>
								<div class="navLateral-body-cr">
									EMPLEADOS
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('6')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=ubicaciones" class="full-width">
								<div class="navLateral-body-cl">
									<i class="bi bi-clipboard2-pulse"></i>
								</div>
								<div class="navLateral-body-cr">
									HISTORIALES
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('7')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="?pagina=servicios" class="full-width">
								<div class="navLateral-body-cl">
									<i class="bi bi-gear-fill"></i>
								</div>
								<div class="navLateral-body-cr">
									SERVICIOS
								</div>
							</a>
						</li>
					<?php endif; ?>

					<?php if (tienePermiso('8') || tienePermiso('9')): ?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="#!" class="full-width btn-subMenu">
								<div class="navLateral-body-cl">
									<i class="bi bi-box-seam"></i>
								</div>
								<div class="navLateral-body-cr">
									RECURSOS
								</div>
								<span class="zmdi zmdi-chevron-left"></span>
							</a>
							<ul class="full-width menu-principal sub-menu-options">
								<?php if (tienePermiso('8')): ?>
									<li class="full-width">
										<a href="?pagina=gestionarInsumos" class="full-width">
											<div class="navLateral-body-cl">
												<i class="bi bi-box-seam-fill"></i>
											</div>
											<div class="navLateral-body-cr">
												GESTIONAR INSUMOS
											</div>
										</a>
									</li>
								<?php endif; ?>
								
								<?php if (tienePermiso('9')): ?>
									<li class="full-width">
										<a href="?pagina=gestionarEquipos" class="full-width">
											<div class="navLateral-body-cl">
												<i class="bi bi-tools"></i>
											</div>
											<div class="navLateral-body-cr">
												GESTIONAR EQUIPOS
											</div>
										</a>
									</li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
					<?php if (tienePermiso('10') || tienePermiso('11') || tienePermiso('12') || tienePermiso('13') || tienePermiso('14') || tienePermiso('15') || tienePermiso('16')) : ?>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-money"></i>
							</div>
							<div class="navLateral-body-cr">
								TRANSACCIONES
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">
							<?php if (tienePermiso('10')) : ?>
								<li class="full-width">
									<a href="?pagina=cuentas" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-credit-card-fill"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											CUENTAS
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('11')) : ?>
								<li class="full-width">
									<a href="?pagina=ingresos" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-arrow-down-circle-fill"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											INGRESOS
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('12')) : ?>
								<li class="full-width">
									<a href="?pagina=egresos" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-arrow-up-circle-fill"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											EGRESOS
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('13')) : ?>
								<li class="full-width">
									<a href="?pagina=cxc" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-wallet2"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											CUENTAS POR COBRAR
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('14')) : ?>
								<li class="full-width">
									<a href="?pagina=movimientos" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-arrow-left-right"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											MOVIMIENTOS
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('15')) : ?>
								<li class="full-width">
									<a href="?pagina=dashboard" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-graph-up"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											DASHBOARD
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('16')) : ?>
								<li class="full-width">
									<a href="?pagina=reportes_cuentas" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-file-earmark-pdf"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											REPORTES CUENTAS
										</div>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (tienePermiso('17') || tienePermiso('18') || tienePermiso('19')) : ?>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-settings"></i>
							</div>
							<div class="navLateral-body-cr" style="color: white;">
								ADMINISTRADOR
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">
							<?php if (tienePermiso('17')) : ?>
								<li class="full-width">
									<a href="?pagina=roles" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-person-fill-gear"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											ROLES
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('18')) : ?>
								<li class="full-width">
									<a href="?pagina=usuario" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-person-fill-gear"></i>
										</div>
										<div class="navLateral-body-cr" style="color: white;">
											USUARIOS
										</div>
									</a>
								</li>
							<?php endif; ?>
							<?php if (tienePermiso('19')) : ?>
								<li class="full-width">
									<a href="?pagina=bitacora" class="full-width">
										<div class="navLateral-body-cl">
											<i class="bi bi-file-earmark-text"></i>
										</div>
										<div class="navLateral-body-cr">
											BITACORA
										</div>
									</a>
								</li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
				</ul>
			</nav>
		</div>
	</section>

	<!-- Contenido de la página -->
	<section class="full-width pageContent">
		<!-- Barra de navegación -->
		<div class="full-width navBar">
			<div class="full-width navBar-options">
				<i class="zmdi zmdi-swap btn-menu" id="btn-menu"></i>	
				<div class="mdl-tooltip" for="btn-menu">MENU</div>
				<nav class="navBar-options-list">
					<ul class="list-unstyle">
						<li class="manual" id="btn-manual">
							<a href="otros/archivos/manual.pdf" target="_blank" style="color: white;" ><i class="bi bi-question-circle"></i></a>
							<div class="mdl-tooltip" for="btn-manual">Manual de Ayuda</div>
						</li>

						<li class="btn-Notification" id="notifications">
							<i class="zmdi zmdi-notifications"></i>
							<div class="mdl-tooltip" for="notifications">Notificaciones</div>
						</li>

						<li class="btn-Perfil" id="btn-editar-perfil">
							<a href="?pagina=perfil"><i class="zmdi zmdi-account-circle"></i></a>
							<div class="mdl-tooltip" for="btn-editar-perfil">Editar Perfil</div>
						</li>	
						<li class="btn-exit" id="btn-exit">
							<a href="#" id="cerrarSesion"><i class="zmdi zmdi-power"></i></a>
							<div class="mdl-tooltip" for="btn-exit">Cerrar Sesión</div>
						</li>
						<li class="text-condensedLight noLink" ><small><?php echo ucfirst($_SESSION['nombre_rol']); ?></small></li>
						<li class="noLink">
							<figure>
								<!-- Imagen de usuario en la barra de navegación -->
								<img src="<?php echo isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'otros/img/menu/avatar.jpg'; ?>" alt="Avatar" class="img-responsive navBar-options-img">
							</figure>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<script type="text/javascript" src="js/cerrarsesion.js"></script>
