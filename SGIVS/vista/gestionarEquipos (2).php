<?php 
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?>
<div class="container"> 
	<br>
	<ul class="nav nav-tabs" id="myTab" role="tablist" >
		<li class="nav-item" role="presentation">
			<a class="nav-link active" id="equipos-tab" data-toggle="tab" href="#equipos" role="tab" aria-controls="equipos" aria-selected="true">Equipos</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" id="entradas-tab" data-toggle="tab" href="#entradas" role="tab" aria-controls="entradas" aria-selected="false">Entradas de Equipos</a>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="equipos" role="tabpanel" aria-labelledby="equipos-tab">
			<h1>Equipos</h1> 
			<div class="container mt-3">
				<div class="row mt-1 justify-content-center">
					<div class="col-md-2 text-center">
						<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Equipo">
							<i class="bi bi-plus-square"></i>
						</button>            
					</div>
					<div class="col-md-2 text-center">
						<button type="button" class="btn-sm btn-warning w-75 small-width" id="generar_reporte" title="Generar Reporte PDF">
							<i class="bi bi-file-pdf"></i>
						</button>
					</div>              
				</div>
				<div class="table-responsive" id="tt">
					<table class="table table-striped table-hover table-center" id="tablaEquipos">
						<thead class="tableh">
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Código</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Marca</th>
								<th class="text-center">Modelo</th>
								<th class="text-center">Cantidad</th>
								<th class="text-center">Precio</th>
								<th class="text-center">Imagen</th>
								<th class="text-center">Acciones</th>						
							</tr>
						</thead>
						<tbody id="resultadoconsulta">			  
						</tbody>
					</table>
				</div>
			</div>
		</div> 
		<div class="tab-pane fade" id="entradas" role="tabpanel" aria-labelledby="entradas-tab">
			<h1>Entradas de Equipos</h1> 
			<div class="container mt-3">
				<div class="row mt-1 justify-content-center">
					<div class="col-md-2 text-center">
						<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir2" title="Registrar Equipo">
							<i class="bi bi-plus-square"></i>
						</button>
					</div>              
				</div>
				<div class="table-responsive" id="tt">
					<table class="table table-striped table-hover table-center" id="tablaSalidaEquipos">
						<thead class="tableh">
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Fecha</th>
								<th class="text-center">Nota de entrega</th>
								<th class="text-center">Equipos</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody id="resultadoconsulta2">			  
						</tbody>
					</table>
				</div>
			</div>
		</div> 
	</div>
</div> 
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1"> 
	<div class="modal-dialog modal-lg" role="document"id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"> Equipo</h5> 
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data"> 
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion"> 
					<div class="container">	
						<div class="row mb-3">
							<div class="col-md-3">
								<label for="codigo">Código</label>
								<input class="form-control" type="text" id="codigo" placeholder="Código obligatorio" title="El codigo del equipo no puede ser modificado..."/>
								<span id="scodigo"></span>
							</div>
							<div class="col-md-3">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre" placeholder="Nombre obligatorio" />
								<span id="snombre"></span>
							</div>
							<div class="col-md-3">
								<label for="marca">Marca</label>
								<input class="form-control" type="text" id="marca"/>
								<span id="smarca"></span>
							</div>
							<div class="col-md-3">
								<label for="modelo">Modelo</label>
								<input class="form-control" type="text" id="modelo" name="modelo"/>
								<span id="smodelo"></span>
							</div>							
						</div>
						<div class="row mb-3">
							<div class="col-md-3">
								<label for="cantidad">Cantidad</label>
								<input class="form-control" type="text" id="cantidad" name="cantidad" placeholder="existencia del equipo obligatorio"/>
								<span id="scantidad"></span>
							</div>
							<div class="col-md-3">
								<label for="precio">Precio</label>
								<input class="form-control" type="text" id="precio" name="precio" placeholder="precio del equipo obligatorio"/>
								<span id="sprecio"></span>
							</div>
							<div class="col-md-4">
								<label for="imagen">Imagen del equipo</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen"accept=".png,.jpg,.jpeg" />
							</div>
							<div class="col-md-2">
								<span id="simagen"></span>
								<img id="imagen_actual" src="" alt="Imagen del equipo" class="img"/> 
							</div>						
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
					<button type="button" class="btn btn-success bi bi-check-square" id="proceso"></button>
				</div>
			</div>	
		</form>
	</div> 
</div>
</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog"  id="modal2">
	<div class=" modal-dialog modal-xl" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i></i> Entrada de Equipos</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	
						<div class="row">
							<div class="col-md-12 input-group">
								<input class="form-control" type="text" id="codigoEquipo" name="codigoEquipo" placeholder="Codigo de equipo..."  />
								<button type="button" class="btn btn-primary" id="listadoDeEquipos" name="listadoDeEquipos"  title="Listado de equipo"><i class='bi bi-search'></i> Buscar Equipo</button>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-5">
								<label for="nota_entrega"><strong>Nota de entrega</strong></label>
								<div class="d-flex align-items-center">
									<input class="col-md-11 inpImg" type="file"  id="nota_entrega" name="nota_entrega"/>
									<i class="bi bi-check-circle-fill text-success ms-2" id="check_nota_entrega"></i>
								</div>
							</div>					
						</div>
						<br>
						<div class="row">
							<h5><strong>Equipos seleccionados</strong></h5>
							<div class="table-responsive" id="tt">
								<table class="table table-striped table-hover table-center ">
									<thead class="tableh">
										<tr>
											<th class="text-center" >Codigo</th>
											<th class="text-center" >Nombre</th>
											<th class="text-center" >Cantidad</th>
											<th class="text-center" >Precio</th>
											<th class="text-center" >Sub Total</th>
											<th class="text-center" >X</th>
										</tr>
									</thead>
									<tbody id="detalledeventa">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer  justify-content-between">
					<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
					<button type="button" class="btn btn-success bi bi-check-square" id="entrada" name="entrada">INCLUIR</button>
				</div>
			</div>	
		</form>
	</div> 
</div>
<div class="modal fade" tabindex="-1" role="dialog"  id="modalEquipos">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Listado de equipos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<div class="col-12">
						<input type="text" class="form-control" id="buscarEquipos" placeholder="Buscar equipo...">
					</div>
				</div>
				<div class="table-responsive" style="max-height: 60vh;">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead-light sticky-top bg-light">
							<tr>
								<th style="display:none">Id</th>
								<th class="text-center">Código</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Marca</th>
								<th class="text-center">Modelo</th>						
								<th class="text-center">Existencia</th>
								<th class="text-center">Precio</th>
								<th class="text-center">Imagen</th>
							</tr>
						</thead>
						<tbody id="listadoEquipos"> 
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-square"></i> Cerrar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalReporte">
	<div class="modal-dialog modal-smarll" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title">Generar Reporte de Equipos</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm">
				<form method="post" id="formReporte" target="_blank">
					<input type="hidden" name="accion" value="reporte_equipos">
					<div class="row">
						<div class="col-md-6">
							<label for="codigo_reporte">Código</label>
							<input class="form-control" type="text" id="codigo_reporte" name="codigo" placeholder="Código del equipo..."/>
						</div>
						<div class="col-md-6">
							<label for="nombre_reporte">Nombre</label>
							<input class="form-control" type="text" id="nombre_reporte" name="nombre" placeholder="Nombre del equipo..."/>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-md-6">
							<label for="marca_reporte">Marca</label>
							<input class="form-control" type="text" id="marca_reporte" name="marca" placeholder="Marca del equipo..."/>
						</div>
						<div class="col-md-6">
							<label for="modelo_reporte">Modelo</label>
							<input class="form-control" type="text" id="modelo_reporte" name="modelo" placeholder="Modelo del equipo..."/>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-md-6">
							<label for="cantidad_reporte">Cantidad</label>
							<input class="form-control" type="text" id="cantidad_reporte" name="cantidad" placeholder="Cantidad..."/>
						</div>
						<div class="col-md-6">
							<label for="precio_reporte">Precio</label>
							<input class="form-control" type="text" id="precio_reporte" name="precio" placeholder="Precio..."/>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
						<button type="submit" class="btn btn-warning"><i class="bi bi-file-pdf"></i> Generar PDF</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</section>
</section>
<div id="loader" class="loader-container">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
<script type="text/javascript" src="js/gestionarEquipos.js"></script>
<link href="css/gestionarEquipos.css" rel="stylesheet" />
</body>
</html>