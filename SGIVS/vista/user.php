<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común de la página 
require_once('comunes/menu.php');//Incluye el menú común de la página 
?> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
<div class="container"> 
	<div class="container">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-people-fill me-2"></i> Gestionar Pacientes</h1>
			<div>
				<button type="button" class="btn btn-info me-2" id="incluir">
					<i class="bi bi-plus-circle me-1"></i> Ingresar Paciente
				</button>
			</div>
		</div>
	</div>
	<!-- Tabla de pacientes -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-info">Listado de Pacientes</h6>
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
						<th class="text-center">#</th>
						<th class="text-center">Usuario</th>
						<th class="text-center">Nombre y Apellido</th>
						<th class="text-center">Rol</th>
						<th class="text-center">Status</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody id="resultadoconsulta">
					<!-- Aquí se cargarán dinámicamente los datos de los pacientes -->
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div> 
<!-- Modal para agregar o editar pacientes -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class="modal-dialog modal-lg" role="document" id="lm">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="bi bi-people-fill me-2"></i> Paciente</h5>
			</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<!-- Formulario para los datos del paciente -->
				<form method="post" id="f" autocomplete="off"  enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container">	
						<!-- Campos para cedula, nombre y apellido -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="user">Usuario</label>
								<input class="form-control" type="text" id="user" title="El usuario no puede ser modificado..." placeholder="JhonDoe18" required/>
								<span id="suser"></span>
							</div>
							<div class="col-md-6">
								<label for="name">Nombre y Apellido</label>
								<input class="form-control" type="text" id="name"  placeholder="Jhon Doe" required/>
								<span id="sname"></span>
							</div>
						</div>
						<!-- Campos para fecha de nacimiento, genero -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="password">Contraseña</label>
								<input class="form-control" type="password" id="password" title="contraseña" required/>
								<span id="spassword"></span>
							</div>
							<div class="col-md-6">
								<label for="password2">Repita la contraseña</label>
								<input class="form-control" type="password" id="password2" title="contraseña" required/>
								<span id="spassword2"></span>
							</div>
						</div>
						<!-- Campos para alergias y antecedentes familiares -->
						<div class="row mb-3">
							<div class="col-md-8">
								<label for="id_rol_user">Rol</label>
								<select class="form-select" id="id_rol_user"required/>
									<option value="">Seleccione una opción </option>
									<option value="1">Administrador</option>
									<option value="2">Recepcionista</option>
									<option value="3">Doctor </option>
								</select>
                                <div class="invalid-feedback">El Rol es obligatorio</div>
							</div>
							<div class="col-md-4">
								<label for="status">Status</label>
								<select class="form-select" id="status"required/>
									<option value="">Seleccione una opción </option>
									<option value="0">Activo</option>
									<option value="1">Inactivo</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
							</div>
						</div>
						<!-- Botones del modal -->
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cerrar</button>
							<button type="button" class="btn btn-info bi bi-plus-circle me-1" id="proceso"></button>
						</div>
					</div>	
				</form>
			</div> 
		</div>
	</div>
</div>
</section>
</section>
<script type="text/javascript" src="js/user.js"></script><!-- Inclusión del archivo JavaScript de pacientes -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html> 