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
				<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person-fill me-2"></i> GESTIONAR EMPLEADOS</h1>
				<div>
					<button type="button" class="btn btn-outline-info me-2" id="incluir">
						<i class="bi bi-plus-circle me-1"></i> INGRESAR EMPLEADO
					</button>
				</div>
			</div>
		</div> 
	<!-- Tabla de empleados -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-info">LISTADO DE EMPLEADOS</h6>
			<div class="dropdown no-arrow">
				<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
					<i class="bi bi-three-dots-vertical"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right shadow">
					<a class="dropdown-item" href="#" id="exportarExcel"><i class="bi bi-file-excel me-2"></i>EXPORTAR A
						EXCEL</a>
					<a class="dropdown-item" href="#" id="imprimirListado"><i class="bi bi-printer me-2"></i>IMPRIMIR
						LISTADO</a>
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
								<th class="text-center" style="display: none;">RIF</th>
								<th class="text-center">CEDULA</th>
								<th class="text-center">NOMBRE Y APELLIDO</th>
								<th class="text-center" style="display: none;">FECHA DE NACIMIENTO</th>
								<th class="text-center" style="display: none;">EDAD</th>
								<th class="text-center" style="display: none;">GENERO</th>
								<th class="text-center" style="display: none;">EMAIL</th>
								<th class="text-center">TELEFONO</th>
								<th class="text-center" style="display: none;">DIRECCION</th>
								<th class="text-center" style="display: none;">FECHA DE CONTRATACION</th>
								<th class="text-center">CARGO</th>
								<th class="text-center" style="display: none;">SUELDO</th>
								<th class="text-center">ACCIONES</th>
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
			<div class="modal-dialog modal-lg" role="document" id="lm">
					<div class="modal-header bg-info text-white">
						<h5 class="modal-title"><i class="bi bi-person-fill me-2"></i> EMPLEADO</h5>
					</div>
				<div class="modal-content">
					<div class="container" id="mtm"> 
						<!-- Formulario para los datos del empleado -->
						<form method="post" id="f" autocomplete="off"  enctype="multipart/form-data">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
							<div class="container">	
								<div class="row mb-3">
									<div class="col-md-6" title="El tipo de documento no puede ser modificado...">
										<label for="tipo_rif">TIPO DE RIF</label>
										<select class="form-control" id="tipo_rif">
											<option value="seleccionar" selected disabled>SELECCIONE UNA OPCION</option>
											<option value="V" title="Persona Natural">PERSONA NATURAL</option>
											<option value="J" title="Persona Juridica">PERSONA JURIDICA</option>
										</select>
									</div>
									<div class="col-md-6">
										<label for="rif">RIF</label>
										<input class="form-control" type="text" id="rif" title="El número de RIF no puede ser modificado..." placeholder="Ejemplo: 123456789" maxlength="11"/>
										<p id="srif"></p>
									</div>
								</div>
								<!-- Campos para cedula, nombre y apellido -->
								<div class="row mb-3">
									<div class="col-md-6" title="El tipo de documento no puede ser modificado...">
										<label for="tipo_documento">TIPO DE DOCUMENTO</label>
										<select class="form-control" id="tipo_documento">
											<option value="seleccionar" selected disabled>SELECCIONE UNA OPCION</option>
											<option value="V" title="Cédula Venezolana">VENEZOLANO</option>
											<option value="E" title="Cédula Extranjera">EXTRANJERO</option>
										</select>
									</div>
									<div class="col-md-6">
										<label for="cedula">DOCUMENTO</label>
										<input class="form-control" type="text" id="cedula" title="El número de cédula no puede ser modificado..." placeholder="Ejemplo: 12345678" minlength="7" maxlength="11"/>
										<p id="scedula"></p>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-md-6">
										<label for="nombre">NOMBRE</label>
										<input class="form-control" type="text" id="nombre"  placeholder="Ejemplo: Jhon" maxlength="20" required/>
										<p id="snombre"></p>
									</div>
									<div class="col-md-6">
										<label for="apellido">APELLIDO</label>
										<input class="form-control" type="text" id="apellido" placeholder="Ejemplo: Doe" maxlength="20" required/>
										<p id="sapellido"></p>
									</div>
								</div>
								<!-- Campos para fecha de nacimiento, edad y genero-->
								<div class="row mb-3">
									<div class="col-md-4">
										<label for="fecha_nacimiento">FECHA DE NACIMIENTO</label>
										<input class="form-control" type="date" id="fecha_nacimiento" title="Fecha de nacimiento del empleado" required/>
										<p id="sfecha_nacimiento"></p>
									</div>
									<div class="col-md-4">
										<label for="edad">EDAD</label>
										<input class="form-control" type="text" id="edad" placeholder="Edad del empleado" style="text-align:center;" disabled/>
										<p id="sedad"></p>
									</div>
									<div class="col-md-4" title="El tipo de género no puede ser modificado...">
										<label for="genero">GENERO</label>
										<select class="form-control" id="genero" title="Género del paciente" required>
											<option value="seleccionar" selected disabled>SELECCIONE UNA OPCION</option>
											<option value="F"title="Género Femenino">FEMENINO</option>
											<option value="M"title="Género Masculono">MASCULINO</option>
											<option value="O"title="Otro">OTRO</option>
										</select>
									</div>
								</div>
								<!-- Campos para email y teléfono -->
								<div class="row mb-3">
									<div class="col-md-8">
										<label for="email">CORREO</label>
										<input class="form-control" type="email" id="email" maxlength="100" placeholder="Ejemplo@email.com" autocomplete="off"/>
										<p id="semail"></p>
									</div>
									<div class="col-md-4">
										<label for="telefono">TELEFONO</label>
										<input class="form-control" type="text" id="telefono" maxlength="15"  placeholder="Ejemplo: 04123456789" required/>
										<p id="stelefono"></p>
									</div>
								</div>
								<!-- Campo para dirección-->
								<div class="row mb-3">
									<div class="col-md-12">
										<label for="direccion">DIRECCION</label>
										<input class="form-control" type="text" id="direccion" maxlength="100" placeholder="Dirección de su domicilio" required/>
										<p id="sdireccion"></p>
									</div>
								</div>	
								<!-- Campo para fecha de contratacion, cargo y salario-->
								<div class="row mb-3">
									<div class="col-md-4">
										<label for="fecha_contratacion">FECHA DE CONTRATACION</label>
										<input class="form-control" type="date" id="fecha_contratacion" required/>
										<p id="sfecha_contratacion"></p>
									</div>
									<div class="col-md-4">
										<label for="cargo">CARGO</label>
										<select class="form-control" id="cargo" title="Género del paciente" required/>
											<option value="" selected disabled>SELECCIONE UNA OPCION</option>
											<option value="DOCTOR"title="DOCTOR">DOCTOR</option>
											<option value="ASISTENTE"title="ASISTENTE">ASISTENTE</option>
											<option value="LIMPIEZA"title="LIMPIEZA">LIMPIEZA</option>
											<option value="RECEPCIONISTA"title="RECEPCIONISTA">RECEPCIONISTA</option>
											<option value="RADIOLOGO"title="RADIOLOGO">RADIOLOGO</option>
											<option value="ESPECIALISTA A"title="ESPECIALISTA A">ESPECIALISTA A</option>
											<option value="ESPECIALISTA B"title="ESPECIALISTA B">ESPECIALISTA B</option>
										</select>
										<p id="scargo"></p>
									</div>
									<div class="col-md-4">
										<label for="salario">SUELDO</label>
										<input class="form-control" type="text" id="salario" placeholder="Ejemplo: 1000.00" required/>
										<p id="ssalario"></p>
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
		<!-- Modal Detalle Empleado -->
		<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="detalleEmpleadoLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-info text-white">
						<h5 class="modal-title" id="detalleEmpleadoLabel"><i class="bi bi-person-lines-fill me-2"></i> Detalle del Empleado</h5>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row mb-2">
								<div class="col-md-4"><label class="fw-bold">RIF:</label><p id="detalle_rif"></p></div>
								<div class="col-md-4"><label class="fw-bold">CÉDULA:</label> <p id="detalle_cedula"></p></div>
								<div class="col-md-4"><label class="fw-bold">NOMBRE Y APELLIDO:</label> <p id="detalle_nombre"></p></div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4"><label class="fw-bold">FECHA DE NACIMIENTO:</label> <p id="detalle_fecha_nacimiento"></p></div>
								<div class="col-md-4"><label class="fw-bold">EDAD:</label> <p id="detalle_edad"></p></div>
								<div class="col-md-4"><label class="fw-bold">GÉNERO:</label> <p id="detalle_genero"></p></div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4"><label class="fw-bold">CORREO:</label> <p id="detalle_email"></p></div>
								<div class="col-md-4"><label class="fw-bold">TELEFONO:</label> <p id="detalle_telefono"></p></div>
								<div class="col-md-4"><label class="fw-bold">DIRECCION:</label> <p id="detalle_direccion"></p></div>
							</div>
							<div class="row mb-2">
								<div class="col-md-4"><label class="fw-bold">FECHA DE CONTRATACION:</label> <p id="detalle_fecha_contratacion"></p></div>
								<div class="col-md-4"><label class="fw-bold">CARGO:</label> <p id="detalle_cargo"></p></div>
								<div class="col-md-4"><label class="fw-bold">SUELDO:</label> <p id="detalle_salario"></p></div>
							</div>
						</div>
					</div>			
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cerrar</button>
					</div>
				</div>
			</div>
		</div>
<!-- Fin Modal Detalle Empleado -->
		</section>
		</section>
		<script type="text/javascript" src="js/empleados.js"></script><!-- Inclusión del archivo JavaScript de pacientes -->
		<div id="loader" class="loader-container" style="display: none;">
			<div class="loader"></div>
			<p>Procesando solicitud...</p>
		</div>
	</body>
</html>