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
<br>
<div class="container-fluid">
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
				<!-- Agrega esto antes de la tabla -->
				<div class="mb-3">
					<input type="text" id="buscadorPacientes" class="form-control" placeholder="Buscar paciente...">
				</div>
				<table class="table table-bordered table-hover" id="tablaPacientes" width="100%" cellspacing="0">
					<thead class="table-light">
						<tr>
							<th>Numero</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Apellido</th>
							<th class="text-center">Teléfono</th>
							<th class="text-center">Correo</th>
							<th class="text-center">Acciones</th>
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
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
			</div>

			<div id="generar-pdf" class="modal-content">
				<div class="modal-body">
					<!-- Contenido del odontograma se mantiene igual -->
					<iframe src="index.html" width="100%" height="500px" frameborder="0"></iframe>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-danger" id="generar-pdf">
						<i class="bi bi-file-pdf"></i> Guardar como PDF
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal de Historial -->
<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="bi bi-clipboard2-pulse me-2"></i>Historial del Paciente</h5>
				<div class="col-0">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
			</div>
			<div class="modal-body">

				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="accion" id="accion">
					<div class="card shadow mb-4" class="card-mb" name="Datos de paciente" id="pacientecard">
						<div class="font-weight-bold text-center" class="h13 mb-0 text-gray-800">Datos del Paciente
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
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

							<div class="row mb">
								<div class="col-md-6">
									<label for="PersonaContacto" class="form-label">Numero de contacto
										(Emergencias)</label>
									<input class="form-control" type="text" id="PersonaContacto" name="PersonaContacto"
										required>
									<div class="invalid-feedback">La persona de contacto es obligatoria</div>
								</div>
								<div class="col-md-6">
									<label for="telefono" class="form-label">Teléfono(personal)</label>
									<input class="form-control" type="tel" id="telefono" name="telefono">
								</div>

							</div>

							<div class="row mb-3">
								<div class="col-md-4">
									<label for="Edad" class="form-label">Edad</label>
									<input class="form-control" type="number" id="Edad" name="Edad">
								</div>

								<div class="col-md-4">
									<label for="correo" class="form-label">Correo</label>
									<input class="form-control" type="email" id="correo" name="correo">
								</div>
							</div>
						</div>
					</div>
					<div class="card shadow mb-4" class="card-mb" name="Datos de paciente" id="pacientecard">
						<div class="font-weight-bold text-center" class="h13 mb-0 text-gray-800">Datos de la Consulta
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="motivo" class="form-label">Motivo de Consulta</label>
								<input class="form-control" type="text" id="motivo" name="motivo">
							</div>
							<div class="col-md-6">
								<label for="diagnostico" class="form-label">Diagnóstico</label>
								<input class="form-control" type="text" id="diagnostico" name="diagnostico">
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-4">
								<label for="tratamiento" class="form-label">Tratamiento</label>
								<input class="form-control" type="text" id="tratamiento" name="tratamiento">
							</div>
							<div class="col-md-4">
								<label for="medicamentos" class="form-label">Medicamentos</label>
								<input class="form-control" type="text" id="medicamentos" name="medicamentos">
							</div>
							<div class="col-md-4">
								<label for="dientesafectados" class="form-label">Dientes Afectados</label>
								<input class="form-control" type="text" id="dientesafectados" name="dientesafectados">
							</div>
							<div class="mb-3">
								<label for="antecedentes" class="form-label">Antecedentes Médicos</label>
								<textarea class="form-control" id="antecedentes" name="antecedentes"
									rows="3"></textarea>
							</div>
							<div class="row">
								<div class="mb-3">
									<label for="fechaconsulta" class="form-label">Fecha de Consulta</label>
									<input class="form-control" type="date" id="fechaconsulta" name="fechaconsulta">
								</div>
								<div class="mb-3">
									<label for="proximacita" class="form-label">Próxima Cita</label>
									<input class="form-control" type="date" id="proximacita" name="proximacita">
								</div>
							</div>
							<div class="mb-3">
								<label for="observaciones" class="form-label">Observaciones</label>
								<textarea class="form-control" id="observaciones" name="observaciones"
									rows="3"></textarea>
								<!--<div class="row mb-3">
									<div class="col-md-6">
										<label for="imagen" class="form-label">Fotografía</label>
										<input class="form-control" type="file" id="imagen" name="imagen"
											accept=".png,.jpg,.jpeg">
									</div>
									<div class="col-md-6 text-center">
										<img id="imagen_actual" src="" alt="Foto del paciente"
											class="img-thumbnail mt-2" style="max-height: 150px;">
									</div>
								</div> -->
							</div>
				</form>
			</div>
			<div class="modal-footer">

				<div class="row mb-3">

					<div class="col-md-3">
						<button type="button" class="btn btn-info" id="proceso">
							<i class="bi bi-check-circle me-1"></i> Guardar
						</button>
					</div>

				</div>
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