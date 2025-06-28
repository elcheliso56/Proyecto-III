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
				<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person-fill me-2"></i> GESTIONAR PACIENTES</h1>
			</div>
		</div> 
	<!-- Tabla de empleados -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-info">LISTADO DE PACIENTES</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-center" id="tablapaciente">
					<thead class="table-light">
						<tr>
							<!-- Encabezados de la tabla -->
							<th class="text-center">#</th>
							<th class="text-center" style="display: none;">ID PACIENTE</th>
							<th class="text-center">CEDULA</th>
							<th class="text-center">NOMBRE Y APELLIDO</th>
							<th class="text-center" style="display: none;">FECHA DE NACIMIENTO</th>
							<th class="text-center" style="display: none;">EDAD</th>
							<th class="text-center">GENERO</th>
							<th class="text-center" style="display: none;">EMAIL</th>
                            <th class="text-center">TELEFONO</th>
                            <th class="text-center" style="display: none;">CONTACTO DE EMERGENCIA</th>
							<th class="text-center" style="display: none;">DIRECCION</th>
                            <th class="text-center" style="display: none;">OCUPACION</th>
                            <th class="text-center" style="display: none;">ALERGIAS</th>
                            <th class="text-center" style="display: none;">ANTECEDENTES</th>
                            <th class="text-center" style="display: none;">MEDICAMENTOS</th>
                            <th class="text-center">FECHA DE REGISTRO</th>
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
	<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post" id="f" autocomplete="off"  enctype="multipart/form-data">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title text-center bi bi-person-fill me-2"> PACIENTE</h5>
                        <div>
                            <label for="id_paciente">CODIGO</label>
                            <input autocomplete="off" type="text" id="id_paciente" style="text-align:center;color:black;">
                        </div>  
                    </div>
					<input autocomplete="off"type="text" class="form-control" name="accion" id="accion">
                    <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                        <!-- Datos del Paciente -->
                        <div class="card shadow row mb-4" id="pacientecard">
                            <br>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-1">
                                    <label for="tipo_documento">TIPO</label>
                                    <input autocomplete="off" class="form-control" type="text" id="tipo_documento" title="El número de cédula no puede ser modificado..." placeholder="Ejemplo: 12345678" minlength="7" maxlength="11"/>
                                </div>
                                <div class="col-md-3" style="padding-left:0px;">
                                    <label for="cedula">DOCUMENTO</label>
                                    <input autocomplete="off" class="form-control" type="text" id="cedula" title="El número de cédula no puede ser modificado..." placeholder="Ejemplo: 12345678" minlength="7" maxlength="11"/>
                                    <span id="scedula"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="nombre" class="form-label">NOMBRE</label>
                                    <input autocomplete="off" class="form-control" type="text" id="nombre" name="nombre" required>
                                    <span id="snombre"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="apellido" class="form-label">APELLIDO</label>
                                    <input autocomplete="off" class="form-control" type="text" id="apellido" name="apellido" required>
                                    <span id="sapellido"></span>
                                </div>
							</div>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-4">
                                    <label for="fecha_nacimiento">FECHA DE NACIMIENTO</label>
                                    <input autocomplete="off" class="form-control" type="date" id="fecha_nacimiento" />
                                </div>
                                <div class="col-md-2">
                                    <label for="edad" class="form-label">EDAD</label>
                                    <input autocomplete="off" class="form-control" type="text" id="edad" name="edad" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="clasificacion" class="form-label">CLASIFICACION</label>
                                    <input autocomplete="off" class="form-control" type="text" id="clasificacion" name="clasificacion" required>
                                    <span id="sclasificacion"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="genero" class="form-label">GENERO</label>
                                    <select class="form-select" id="genero" name="genero" required>
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="MASCULINO">MASCULINO</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                    </select>
                                </div>
							</div>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-4">
                                    <label for="email">CORREO</label>
                                    <input autocomplete="off" class="form-control" type="email" id="email" />
                                    <span id="semail"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="telefono" class="form-label">TELEFONO</label>
                                    <input autocomplete="off" class="form-control" type="text" id="telefono" name="telefono" required>
                                    <span id="stelefono"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="contacto_emergencia">CONTACTO DE EMERGENCIA</label>
                                    <input autocomplete="off" class="form-control" type="text" id="contacto_emergencia" />
                                    <span id="scontacto_emergencia"></span>
                                </div>
							</div>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-12">
                                    <label for="direccion" class="form-label">DIRECCION</label>
                                    <input autocomplete="off" class="form-control" type="text" id="direccion" name="direccion" required>
                                    <span id="sdireccion"></span>
                                </div>
							</div>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-12">
                                    <label for="ocupacion">OCUPACION</label>
                                    <input autocomplete="off" class="form-control" type="text" id="ocupacion" />
                                    <span id="socupacion"></span>
                                </div>
							</div>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-6">
                                    <label for="alergias">ALERGIAS</label>
                                    <input autocomplete="off" class="form-control" type="text" id="alergias" />
                                    <span id="salergias"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="antecedentes">ANTECEDENTES</label>
                                    <input autocomplete="off" class="form-control" type="text" id="antecedentes" />
                                    <span id="santecedentes"></span>
                                </div>
							</div>
                            <div class="row mb-3" style="margin-left:1px;">
                                <div class="col-md-6">
                                    <label for="medicamentos">MEDICAMENTOS</label>
                                    <input autocomplete="off" class="form-control" type="text" id="medicamentos" />
                                    <span id="smedicamentos"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_registro">FECHA DE REGISTRO</label>
                                    <input autocomplete="off" class="form-control" type="date" id="fecha_registro" />
                                    <span id="sfecha_registro"></span>
                                </div>
							</div>
                        </div>
                    </div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> CERRAR</button>
						<button type="button" class="btn btn-info bi bi-plus-circle me-1" id="proceso"></button>
					</div>
                </form>
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