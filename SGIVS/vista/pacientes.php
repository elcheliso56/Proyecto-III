<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común de la página 
require_once('comunes/menu.php');//Incluye el menú común de la página 
?> 
<div class="container"> 
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-people-fill me-2"></i> Gestionar Pacientes</h1>
		<div>
			<button type="button" class="btn btn-info me-2" id="incluir">
				<i class="bi bi-plus-circle me-1"></i> Ingresar Paciente
			</button>
		</div>
	</div>
	<!-- Contenedor para la tabla de pacientes -->
	<div class="container">
		<div class="table-responsive" id="tt">
			<!-- Tabla para mostrar la lista de pacientes -->
			<table class="table table-striped table-hover table-center" id="tablacliente">
				<thead>
					<tr>
						<!-- Encabezados de la tabla -->
						<th class="text-center">#</th>
						<th class="text-center">Cédula</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Apellido</th>
						<th class="text-center">Fecha de Nacimiento</th>
						<th class="text-center">Edad</th>
						<th class="text-center">Clasificación</th>
						<th class="text-center">Género</th>
						<th class="text-center">Alergias</th>
						<th class="text-center">Antecedentes</th>
						<th class="text-center">Correo</th>
						<th class="text-center">Telefono</th>
						<th class="text-center">Dirección</th>
						<th class="text-center">Fecha_de Registro</th>
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
							<div class="col-md-4">
								<label for="cedula">Cédula</label>
								<input class="form-control" type="text" id="cedula" title="El número de documento no puede ser modificado..." placeholder="Cédula obligatoria" requerid/>
								<span id="scedula"></span>
							</div>
							<div class="col-md-4">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre"  placeholder="Nombre obligatorio" requerid/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-4">
								<label for="apellido">Apellido</label>
								<input class="form-control" type="text" id="apellido" placeholder="Apellido obligatorio" requerid/>
								<span id="sapellido"></span>
							</div>
						</div>
						<!-- Campos para fecha de nacimiento, genero y tipo de sangre-->
						<div class="row mb-3">
							<div class="col-md-3">
								<label for="fecha_nacimiento">Fecha de Nacimiento</label>
								<input class="form-control" type="date" id="fecha_nacimiento" title="Fecha de nacimiento del paciente" requerid/>
								<span id="sfecha_nacimiento"></span>
							</div>
							<div class="col-md-2">
								<label for="edad">Edad</label>
								<input class="form-control" type="text" id="edad" title="Edad del paciente" style="text-align:center" disabled/>
								<span id="sedad"></span>
							</div>
							<div class="col-md-3">
								<label for="clasificacion">Clasificación</label>
								<input class="form-control" type="text" id="clasificacion" title="Clasificación por Edad" style="text-align:center" disabled/>
								<span id="sclasificacion"></span>
							</div>
							<div class="col-md-4" title="El tipo de género no puede ser modificado...">
								<label for="genero">Género</label>
								<select class="form-control" id="genero" title="Género del paciente" requerid/>
									<option value="seleccionar" selected disabled>Seleccione una opción </option>
									<option value="F"title="Género Femenino">Femenina</option>
									<option value="M"title="Género Masculono">Masculino</option>
									<option value="O"title="Otro">Otro</option>
								</select>
							</div>
						</div>
						<!-- Campos para alergias y antecedentes familiares -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="alergias">Alergia</label>
								<input class="form-control" type="text" id="alergias" placeholder="Ejemplo: Penicilina" />
								<span id="salergias"></span>
							</div>
							<div class="col-md-6">
								<label for="antecedentes">Antecendentes Familiares</label>
								<input class="form-control" type="text" id="antecedentes" placeholder="Ejemplo: Diabetes"/>
								<span id="santecedentes"></span>
							</div>
						</div>
						<!-- Campos para email y teléfono -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="email">Correo</label>
								<input class="form-control" type="text" id="email" placeholder="Ejemplo@email.com"/>
								<span id="semail"></span>
							</div>
							<div class="col-md-6">
								<label for="telefono">Teléfono</label>
								<input class="form-control" type="text" id="telefono" placeholder="Ejemplo: 04123456789" requerid/>
								<span id="stelefono"></span>
							</div>
						</div>
						<!-- Campo para dirección y fecha de registro-->
						<div class="row mb-3">
							<div class="col-md-8">
								<label for="direccion">Dirección</label>
								<input class="form-control" type="text" id="direccion" placeholder="Dirección de su domicilio" requerid/>
								<span id="sdireccion"></span>
							</div>
							<div class="col-md-4">
								<label for="fecha_registro">Fecha de Registro</label>
								<input class="form-control" type="date" id="fecha_registro"/>
								<span id="sfecha_registro"></span>
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
<script type="text/javascript" src="js/pacientes.js"></script><!-- Inclusión del archivo JavaScript de pacientes -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>