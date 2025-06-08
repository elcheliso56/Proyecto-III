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
				<h1 class="h3 mb-0 text-gray-800"><i class="bi bi-person-fill me-2"></i> GESTIONAR USUARIO</h1>
				<div>
					<button type="button" class="btn btn-outline-info me-2" id="incluir">
						<i class="bi bi-plus-circle me-1"></i> INGRESAR USUARIO
					</button>
				</div>
			</div>
		</div> 
	<!-- Tabla de Usuarioss -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex justify-content-between align-items-center">
			<h6 class="m-0 font-weight-bold text-info">LISTADO DE USUARIOS</h6>
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
					<table class="table table-striped table-hover table-center" id="tablausuario">
						<thead class="table-light">
							<tr>
								<!-- Encabezados de la tabla -->
								<th class="text-center">#</th>
								<th class="text-center">NOMBRE Y APELLIDO</th>
								<th class="text-center">ROL</th>
								<th class="text-center">ESTADO</th>
								<th class="text-center">Acciones</th>
								<th class="text-center" style="display: none;">USUARIO</th>
								<th class="text-center" style="display: none;">IMAGEN</th>
							</tr>
						</thead>
						<tbody id="resultadoconsulta">
							<!-- Aquí se cargarán dinámicamente los datos de los Usuarioss -->
						</tbody>
					</table>
			</div>
		</div>
	</div>
		<!-- Modal para agregar o editar Usuarios -->
		<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
			<div class="modal-dialog modal-lg" role="document" id="lm">
					<div class="modal-header bg-info text-white">
						<h5 class="modal-title bi bi-person-fill me-2"> USUARIO</h5>
					</div>
				<div class="modal-content">
					<div class="container" id="mtm"> 
						<!-- Formulario para los datos del Usuarios -->
						<form method="post" id="f" autocomplete="off"  enctype="multipart/form-data">
							<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
							<div class="container">	

								<div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cedula" class="form-label"></label>
                                            <div class="d-flex gap-2 align-items-center">
                                                <button class="btn btn-outline-info flex-shrink-0" type="button" id="listaEmpleados" name="listaEmpleados" title="Listado de Cédulas">
                                                    <i class="bi bi-card-list"></i>
                                                </button>
                                                <input type="text" class="form-control flex-grow-1" id="cedula" name="cedula" placeholder="Cédula" aria-label="Cédula" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="nombre_apellido" class="form-label">NOMBRE Y APELLIDO</label>
                                        <input class="form-control" type="text" id="nombre_apellido" placeholder="Nombre y Apellido" required/>
                                    </div>
								</div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="usuario">USUARIO</label>
                                        <input class="form-control" type="text" id="usuario" placeholder="Usuario" />
                                    </div>
                                    <div class="col-md-6" >
                                        <label for="id_rol">ROL</label>
                                        <select class="form-select" id="id_rol">
                                            <option value="" selected disabled>Seleccione una opción </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="contraseña">CONTRASEÑA</label>
                                        <input class="form-control" type="password" id="contraseña"/>                                        
										<span id="scontraseña"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="repetir_contraseña">REPETIR CONTRASEÑA</label>
                                        <input class="form-control" type="password" id="repetir_contraseña"/>
                                        <span id="srepetir_contraseña"></span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="imagen">IMAGEN DEL USUARIO</label>
                                        <input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg"/>
                                    </div>
                                    <div class="col-md-6">
                                        <span id="simagen"></span>
                                        <img id="imagen_actual" src="" alt="Imagen del usuario" class="img"/> <!-- Imagen actual del usuario -->
                                    </div>
                                </div>

								<div class="row">
									<div class="col-md-12">
									</div>
								</div>
								<!-- Botones del modal -->
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> CERRAR</button>
									<button type="button" class="btn btn-info bi bi-plus-circle me-1" id="proceso"></button>
								</div>
							</div>	
						</form>
					</div> 
				</div>
			</div>
		</div>
        <!-- Modal de Empleados -->
        <div class="modal fade" id="modalusuario" tabindex="-1">
            <div class="modal-dialog modal-lg" role="dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">USUARIOS</h5>
                    </div>
                    <div class="modal-body">
                        <!-- Agregar campo de búsqueda -->
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="buscarEmpleado" placeholder="BUSCAR POR CÉDULA, NOMBRE O APELLIDO...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped-columns table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">CÉDULA</th>
                                        <th class="text-center">NOMBRE</th>
                                        <th class="text-center">APELLIDO</th>
                                    </tr>
                                </thead>
                                <tbody id="tablapaciente">
                                    <!-- Aquí se cargarán dinámicamente los datos de los clientes -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> CERRAR</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ver detalles del usuario -->
        <div class="modal fade" id="modalDetalle" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>DETALLES DEL USUARIO</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <img id="detalleImagen" src="" alt="Imagen del usuario" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                            </div>
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="fw-bold">NOMBRE Y APELLIDO:</label>
                                        <p id="detalleNombreApellido"></p>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="fw-bold">USUARIO:</label>
                                        <p id="detalleUsuario"></p>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="fw-bold">ROL:</label>
                                        <p id="detalleRol"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="fw-bold">ESTADO:</label>
                                        <p id="detalleEstado"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> CERRAR</button>
                    </div>
                </div>
            </div>
        </div>

		</section>
		</section>
		<script type="text/javascript" src="js/usuario.js"></script><!-- Inclusión del archivo JavaScript de pacientes -->
		<div id="loader" class="loader-container" style="display: none;">
			<div class="loader"></div>
			<p>Procesando solicitud...</p>
		</div>
	</body>
</html>