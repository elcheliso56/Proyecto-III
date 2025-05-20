<body>
	<!-- Área de notificaciones -->
	<section class="full-width container-notifications">
		<!-- Fondo de notificaciones -->
		<div class="full-width container-notifications-bg btn-Notification"></div>
		<section class="NotificationArea">
			<!-- Título de notificaciones -->
			<div class="full-width text-center NotificationArea-title tittles">Notificaciones <i
					class="zmdi zmdi-close btn-Notification"></i></div>
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
					<img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>"
						alt="Avatar" class="img-responsive navLateral-body-img">
				</div>
				<figcaption>
					<span>
						<!-- Nombre y tipo de usuario -->
						<?php echo $_SESSION['nombre_usuario']; ?><br>
						<small><?php echo ucfirst($_SESSION['tipo_usuario']); ?></small>
					</span>
				</figcaption>
			</figure>
			<nav class="full-width">
				<ul class="full-width list-unstyle menu-principal">
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=principal" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-view-dashboard"></i>
							</div>
							<div class="navLateral-body-cr" style="color: white;">
								Principal
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=empleados" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-account"></i>
							</div>
							<div class="navLateral-body-cr" style="color: white;">
								Empleados
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=pacientes" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-accounts"></i>
							</div>
							<div class="navLateral-body-cr" style="color: white;">
								Pacientes
							</div>
						</a>
					</li>

					
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=ubicaciones" class="full-width">
							<div class="navLateral-body-cl">
								<i class="bi bi-clipboard2-pulse"></i>
							</div>
							<div class="navLateral-body-cr" style="color: white;">
								Historiales
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="bi bi-cash-stack"></i>
							</div>
							<div class="navLateral-body-cr"style="color: white;">
								Transacciones
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">

							<li class="full-width">
								<a href="?pagina=ingresos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-arrow-down-circle-fill"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Ingresos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=egresos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-arrow-up-circle-fill"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Egresos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=movimientos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-arrow-left-right"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Movimientos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=dashboard" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-graph-up"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Dashboard
									</div>
								</a>
							</li>
						</ul>
					</li>




					<!-- Enlaces a diferentes secciones del menú -->
					<li class="full-width">
						
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">

					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						
					</li>
					<li class="full-width divider-menu-h"></li>
					<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
						<li class="full-width">
							<a href="?pagina=usuarios" class="full-width">
								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-account"></i>
								</div>
								<div class="navLateral-body-cr" style="color: white;">
									Usuarios
								</div>
							</a>
						</li>
						<li class="full-width divider-menu-h"></li>
					<?php endif; ?>
					<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-shopping-cart"></i>
							</div>
							<div class="navLateral-body-cr" style="color: white;">
								Recursos
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">

							<li class="full-width">
								<a href="?pagina=insumos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-bag-check-fill"></i>
									</div>
									<div class="navLateral-body-cr" style="color: white;">
										Insumos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=equipos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-cart-plus-fill"></i>
									</div>
									<div class="navLateral-body-cr" style="color: white;">
										Equipos
									</div>
								</a>
							</li>
							<li class="full-width">
			
							</li>
						</ul>


						<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="bi bi-cash-stack"></i>
							</div>
							<div class="navLateral-body-cr"style="color: white;">
								Transacciones
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">

							<li class="full-width">
								<a href="?pagina=ingresos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-arrow-down-circle-fill"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Ingresos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=egresos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-arrow-up-circle-fill"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Egresos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=movimientos" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-arrow-left-right"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Movimientos
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=dashboard" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-graph-up"></i>
									</div>
									<div class="navLateral-body-cr"style="color: white;">
										Dashboard
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="full-width divider-menu-h"></li>
					<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
						<li class="full-width">
							<a href="?pagina=reportes" class="full-width">
								<div class="navLateral-body-cl">
									<i class="bi bi-file-text-fill"></i>
								</div>
								<div class="navLateral-body-cr" style="color: white;">
									Reportes
								</div>
							</a>
						</li>
						<li class="full-width divider-menu-h"></li>
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
							<a href="otros/archivos/" target="_blank" style="color: white;"><i
									class="bi bi-question-circle"></i></a>
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
						<li class="text-condensedLight noLink">
							<small><?php echo ucfirst($_SESSION['tipo_usuario']); ?></small>
						</li>
						<li class="noLink">
							<figure>
								<!-- Imagen de usuario en la barra de navegación -->
								<img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>"
									alt="Avatar" class="img-responsive navBar-options-img">
							</figure>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<script type="text/javascript" src="js/cerrarsesion.js"></script>