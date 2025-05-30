<html lang="es">
<?php
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?>
<!-- Librerías para captura y PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
	crossorigin="anonymous"></script>
<style>
	#tablacliente th,
	#tablacliente td {
		font-size: 0.95rem;
		vertical-align: middle;
	}

	#tablacliente th {
		padding: 0.5rem 0.3rem;
	}

	#tablacliente td {
		padding: 0.4rem 0.3rem;
	}
</style>
<br>
<div class="container">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-people-fill me-2"></i>Historial de Pacientes</h1>
		<div>
			<button type="button" class="btn btn-info me-2" id="incluir">
				<i class="bi bi-plus-circle me-1"></i> Nuevo Paciente
			</button>
			<button class="btn btn-outline-info" id="odontomodal" data-bs-toggle="modal" data-bs-target="#miModal">
				<i class="bi bi-tooth me-1"></i> Odontograma
			</button>
		</div>
	</div>
	<!-- Tabla de pacientes -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-info">Listado de Pacientes</h6>

		</div>
		<div class="card-body">
			<div class="table-responsive">
				<!-- Agrega esto antes de la tabla -->
				<div class="mb-3">
					<input type="text" id="buscadorPacientes" class="form-control" placeholder="Buscar paciente...">
				</div>
				<table class="table table-bordered table-hover" id="tablaPacientes" width="100%" cellspacing="0">
					<thead class="table-light">
						<tr>
							<th>Numero</th>
							
							<th class="text-center">Acciones</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Apellido</th>
							<th class="text-center">Teléfono</th>
							<th class="text-center">Correo</th>
							<th class="text-center">Edad</th>
							<th class="text-center">Sexo</th>
							<th class="text-center">Ocupación</th>
							<th class="text-center">Persona de Contacto</th>
							<th class="text-center">Diagnóstico</th>
							<th class="text-center">Tratamiento</th>
							<th class="text-center">Medicamentos</th>
							<th class="text-center">Diente Afectado</th>
							<th class="text-center">Antecedentes Médicos</th>
							<th class="text-center">Fecha de Consulta</th>
							<th class="text-center">Próxima Cita</th>
							<th class="text-center">Observaciones</th>
						</tr>
					</thead>
					<tbody id="resultadoconsulta">
						<!-- Datos se cargarán aquí mmm -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	#pacientecard {
		background-color: rgb(232, 236, 240);
		border-radius: 0.5rem;
		padding: 1rem;
		margin-bottom: 1rem;
	}
</style>
<!-- Modal Odontograma  -->
<div class="modal fade" id="miModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="bi bi-clipboard2-pulse me-2"></i>Odontograma</h5>
				<div class="col-0">
				</div>
			</div>
			<!-- Modal Odontograma - Contenido organizado -->
			<div class="modal-body">
				<!-- Datos del paciente para el odontograma (ya están en el formulario superior) -->
				<!-- Contenido del odontograma -->
				<iframe src="index.html" width="100%" height="500px" frameborder="0"></iframe>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
					<i class="bi bi-x-circle"></i> Cerrar
				</button>
				<button type="button" class="btn btn-info" id="generar-pdf">
					<i class="bi bi-file-pdf"></i> Guardar como PDF
				</button>
			</div>
		</div>
	</div>
</div>
</div>
<!-- Modal de Historial -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="bi bi-clipboard2-pulse me-2"></i>Historial del Paciente</h5>

			</div>
			<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
				<input type="hidden" name="accion" id="accion">
				<input type="hidden" name="id" id="id">
				<div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
					<!-- Datos del Paciente -->
					<div class="card shadow mb-4" id="pacientecard">
						<div class="font-weight-bold text-center mb-3">Datos del Paciente</div>
						<div class="row g-3">
							<div class="col-md-6">
								<div class="mb-3">
									<label for="cedula" class="form-label"></label>
									<div class="d-flex gap-2 align-items-center">
										<button class="btn btn-outline-info flex-shrink-0" type="button" id="listadopaciente" name="listadopaciente" title="Listado de Cédulas">
											<i class="bi bi-card-list"></i>
										</button>
										<input type="text" class="form-control flex-grow-1" id="cedula" name="cedula" placeholder="Cédula" aria-label="Cédula" disabled>
									</div>
								</div>
								<label for="nombre" class="form-label">Nombre</label>
								<input class="form-control" type="text" id="nombre" name="nombre" required>
								<div class="invalid-feedback">El nombre es obligatorio</div>
							</div>
							<div class="col-md-6">
								<label for="Apellido" class="form-label">Apellido</label>
								<input class="form-control" type="text" id="Apellido" name="Apellido" required>
								<div class="invalid-feedback">El Apellido es obligatorio</div>
							</div>
							<div class="col-md-6">
								<label for="Ocupacion" class="form-label">Ocupación</label>
								<input class="form-control" type="text" id="Ocupacion" name="Ocupacion" required>
								<div class="invalid-feedback">La ocupación es obligatoria</div>
							</div>
							<div class="col-md-6">
								<label for="Sexo" class="form-label">Sexo</label>
								<select class="form-select" id="Sexo" name="Sexo" required>
									<option value="" selected disabled>Seleccione</option>
									<option value="Masculino">Masculino</option>
									<option value="Femenino">Femenino</option>
								</select>
								<div class="invalid-feedback">El sexo es obligatorio</div>
							</div>
							<div class="col-md-6">
								<label for="PersonaContacto" class="form-label">Número de contacto (Emergencias)</label>
								<input class="form-control" type="text" id="PersonaContacto" name="PersonaContacto" required>
								<div class="invalid-feedback">La persona de contacto es obligatoria</div>
							</div>
							<div class="col-md-6">
								<label for="telefono" class="form-label">Teléfono (personal)</label>
								<input class="form-control" type="tel" id="telefono" name="telefono">
							</div>
							<div class="col-md-4">
								<label for="Edad" class="form-label">Edad</label>
								<input class="form-control" type="number" id="Edad" name="Edad" min="0">
							</div>
							<div class="col-md-8">
								<label for="correo" class="form-label">Correo</label>
								<input class="form-control" type="email" id="correo" name="correo">
							</div>
						</div>
					</div>
					<!-- Datos de la Consulta -->
					<div class="card shadow mb-4" id="pacientecard">
						<div class="font-weight-bold text-center mb-3">Datos de la Consulta</div>
						<div class="row g-3">
							<!--<div class="col-md-6">
								<label for="motivo" class="form-label">Motivo de Consulta</label>
								<input class="form-control" type="text" id="motivo" name="motivo">
							</div> -->
							<div class="col-md-6">
								<label for="diagnostico" class="form-label">Diagnóstico</label>
								<input class="form-control" type="text" id="diagnostico" name="diagnostico">
							</div>
							<div class="col-md-4">
								<label for="tratamiento" class="form-label">Tratamiento</label>
								<input class="form-control" type="text" id="tratamiento" name="tratamiento">
							</div>
							<div class="col-md-4">
								<label for="medicamentos" class="form-label">Medicamentos</label>
								<input class="form-control" type="text" id="medicamentos" name="medicamentos">
							</div>
							<div class="col-md-4">
								<label for="dientesafectados" class="form-label">Diente Afectado</label>
								<select class="form-select" id="dientesafectados" name="dientesafectados" required>
									<option value="" selected disabled>Seleccione</option>
									<?php
									// Piezas dentales permanentes (del 11 al 48)
									for ($i = 11; $i <= 18; $i++) {
										echo "<option value=\"$i\">$i</option>";
									}
									for ($i = 21; $i <= 28; $i++) {
										echo "<option value=\"$i\">$i</option>";
									}
									for ($i = 31; $i <= 38; $i++) {
										echo "<option value=\"$i\">$i</option>";
									}
									for ($i = 41; $i <= 48; $i++) {
										echo "<option value=\"$i\">$i</option>";
									}
									?>
								</select>
							</div>
							<div class="col-12">
								<label for="antecedentes" class="form-label">Antecedentes Médicos</label>
								<textarea class="form-control" id="antecedentes" name="antecedentes" rows="2"></textarea>
							</div>
							<div class="col-md-6">
								<label for="fechaconsulta" class="form-label">Fecha de Consulta</label>
								<input class="form-control" type="date" id="fechaconsulta" name="fechaconsulta">
							</div>
							<div class="col-md-6">
								<label for="proximacita" class="form-label">Próxima Cita</label>
								<input class="form-control" type="date" id="proximacita" name="proximacita">
							</div>
							<div class="col-12">
								<label for="observaciones" class="form-label">Observaciones</label>
								<textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger me-auto" data-bs-dismiss="modal">
					<i class="bi bi-x-circle me-1"></i> Cerrar
				</button>
				<button type="button" class="btn btn-info" id="proceso">
					<i class="bi bi-check-circle me-1"></i> Guardar
				</button>
			</div>

		</div>
	</div>
</div>
<!-- Modal para mostrar campos del modelo del paciente seleccionado -->

<!-- Modal para mostrar campos del modelo del paciente seleccionado -->
<div class="modal fade" id="modalModelo" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="bi bi-list-columns-reverse me-2"></i>Datos del Paciente</h5>
			</div>
			<div class="modal-body">
				<ul class="list-group" id="camposModeloLista">
					<!-- Los datos del paciente se insertarán aquí dinámicamente -->
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
					<i class="bi bi-x-circle"></i> Cerrar
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalpaciente" tabindex="-1">
	<div class="modal-dialog modal-lg" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Pacientes</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-striped-columns table-hover table-sm">
						<thead>
							<tr>
								<th class="text-center">Cédula</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Apellido</th>
								<th class="text-center">Teléfono</th>
							</tr>
						</thead>
						<tbody id="tablapaciente">
							<!-- Aquí se cargarán dinámicamente los datos de los clientes -->
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer justify-content-start">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript" src="js/ubicaciones.js"></script>
<!-- Loader mejorado -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="spinner-border text-info" role="status">
		<span class="visually-hidden">Cargando...</span>
	</div>
	<p class="mt-2">Procesando solicitud...</p>
</div>

</body>

</html>