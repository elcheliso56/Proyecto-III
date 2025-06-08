<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común de la página 
require_once('comunes/menu.php');//Incluye el menú común de la página 
?> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous"/>
	<br>
	<div class="container-fluid"> 
		<div class="container">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person-fill me-2"></i> Gestionar Roles y Permisos de Usuario</h1>
				<div>
					<button type="button" class="btn btn-outline-info me-2" id="incluir">
						<i class="bi bi-plus-circle me-1"></i> Ingresar Rol
					</button>
				</div>
			</div>
		</div> 
	<!-- Tabla de empleados -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-info">Listado de Roles</h6>
			<div class="dropdown no-arrow">
				<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
					<i class="bi bi-three-dots-vertical"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right shadow">
					<a class="dropdown-item" href="#" id="exportarExcel"><i class="bi bi-file-excel me-2"></i>Exportar a
						Excel</a>
					<a class="dropdown-item" href="#" id="imprimirListado"><i class="bi bi-printer me-2"></i>Imprimir
						Listado</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
					<table class="table table-striped table-hover table-center" id="tablacliente">
						<thead class="table-light">
							<tr>
								<!-- Encabezados de la tabla -->
								<th class="text-center" style="display: none;">#</th>
								<th class="text-center">Nombre del Rol</th>
								<th class="text-center">Descripción</th>
								<th class="text-center">Estado</th>
								<th class="text-center">Acciones</th>
							</tr>
						</thead>
						<tbody id="resultadoconsulta">
							<!-- Aquí se cargarán dinámicamente los datos de los Empleados -->
						</tbody>
					</table>
			</div>
		</div>
	</div>
		<!-- Modal para agregar o editar Empleados -->
		<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
			<div class="modal-dialog" role="document" id="lm">
					<div class="modal-header bg-info text-white">
						<h5 class="modal-title"><i class="bi bi-person-fill-gear me-2"></i> Rol</h5>
					</div>
				<div class="modal-content">
					<div class="container" id="mtm"> 
                        
						<form method="post" id="f" autocomplete="off"  enctype="multipart/form-data">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
							<input type="hidden" id="id" name="id">
							<div class="container">
								<div class="row mb-3">
									<div class="col-md-12">
										<label for="nombre_rol">Nombre del Rol</label>
										<input class="form-control" type="text" id="nombre_rol" name="nombre_rol" placeholder="Ejemplo: Administrador"/>
										<span id="snombre_rol" class="text-danger"></span>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-md-12">
										<label for="descripcion">Descripción</label>
										<textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del rol"></textarea>
										<span id="sdescripcion" class="text-danger"></span>
									</div>
								</div>

								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> CERRAR</button>
									<button type="button" class="btn btn-info bi bi-check-circle" id="proceso"></button>
								</div>
							</div>
						</form>
					</div> 
				</div>
			</div>
		</div>

        
		<div class="modal fade" tabindex="-1" role="dialog"  id="modal2">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-info text-white">
						<h5 class="modal-title"><i class="bi bi-shield-lock-fill me-2"></i> Gestionar Permisos del Rol</h5>
					</div>
					<div class="modal-body">
						<form method="post" id="f_permisos" autocomplete="off">
							<input type="hidden" id="id_rol" name="id_rol">
							<div class="container">
								<div class="row mb-2 " id="rol_permisos">
									<!-- Aquí se cargarán dinámicamente los permisos -->
								</div>
							</div>
						</form>
					</div>
                                
					<div class="row">
						<div class="col-md-12">
						</div>
					</div>
                                
					<div class="modal-footer justify-content-between">
					    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> CERRAR</button>
						<button type="button" class="btn btn-info" id="guardar">
							<i class="bi bi-check-circle"></i> INCLUIR
						</button>
					</div>
				</div>
			</div>
		</div>

		</section>
		</section>
		<script type="text/javascript" src="js/roles.js"></script><!-- Inclusión del archivo JavaScript de pacientes -->
		<div id="loader" class="loader-container" style="display: none;">
			<div class="loader"></div>
			<p>Procesando solicitud...</p>
		</div>
	</body>
</html>