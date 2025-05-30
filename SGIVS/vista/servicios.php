<?php 
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?> 
<div class="container"> 
	<h1> Gestionar Servicios</h1>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Servicio"><i class="bi bi-plus-square"></i></button>
			</div>	

                <div class="col-md-2 text-center">
                    <button type="button" class="btn-sm btn-warning w-75 small-width" id="generar_reporte" title="Generar Reporte PDF">
                        <i class="bi bi-file-pdf"></i>
                    </button>
                </div>  


						
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablaServicios">
				<thead class="tableh">
					<tr>
						<th class="text-center">#</th>	
						<th class="text-center">Nombre</th>
						<th class="text-center">Descripción</th>
						<th class="text-center">Precio</th>	
						<th class="text-center">Insumos</th>
						<th class="text-center">Equipos</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody id="resultadoconsulta">			  
				</tbody>
			</table>
		</div>
	</div>
</div> 
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class=" modal-dialog modal-lg" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i></i> Servicio</h5>
		</div>

		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	

						<div class="row mb-3" >
							<div class="col-md-4">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre obligatorio"/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-4">
								<label for="descripcion">Descripción</label>
								<input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción del servicio" />
								<span id="sdescripcion"></span>
							</div>
							<div class="col-md-4">
								<label for="precio">Precio</label>
								<input class="form-control" type="text" id="precio" placeholder="Obligatorio..."/>
								<span id="sprecio"></span>
							</div>							
						</div>
						<div class="row mb-3">
							<div class="col-md-6 input-group">
								<input class="form-control" type="text" id="codigoInsumo" name="cedulacliente" placeholder="Codigo..." />
								<input class="form-control hidden-field" type="text" id="idcliente" name="idcliente"/>
								<button type="button" class="btn btn-primary" id="listadoDeInsumos" name="listadoDeInsumos"  title="Listado de insumos"><i class='bi bi-search'></i> Buscar Insumo</button>
							</div>
							<div class="col-md-6 input-group">
								<input class="form-control" type="text" id="codigoEquipo" name="codigoproducto" placeholder="Codigo..."  />
								<input class="form-control hidden-field" type="text" id="idproducto" name="idproducto"/> 
								<button type="button" class="btn btn-primary" id="listadoDeEquipos" name="listadoDeEquipos"  title="Listado de Equipos"><i class='bi bi-search'></i> Buscar Equipo</button>
							</div>	
						</div>
						<div class="row">
							<h5><strong>Insumos seleccionados</strong></h5>
							<div class="table-responsive" id="tt">
								<table class="table table-striped table-hover table-center ">
									<thead class="tableh">
										<tr>
											<th class="text-center hidden-field">Id</th>
											<th class="text-center" >Codigo</th>
											<th class="text-center" >Nombre</th>
											<th class="text-center" >Cantidad</th>
											<th class="text-center" >Precio</th>
											<th class="text-center" >Sub Total</th>
											<th class="text-center" >X</th>
										</tr>
									</thead>
									<tbody id="InsumosSeleccionados">
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<h5><strong>Equipos seleccionados</strong></h5>
							<div class="table-responsive" id="tt">
								<table class="table table-striped table-hover table-center ">
									<thead class="tableh">
										<tr>
											<th class="text-center hidden-field">Id</th>
											<th class="text-center" >Codigo</th>
											<th class="text-center" >Nombre</th>
											<th class="text-center" >Cantidad</th>
											<th class="text-center" >Precio</th>
											<th class="text-center" >Sub Total</th>
											<th class="text-center" >X</th>
										</tr>
									</thead>
									<tbody id="EquiposSeleccionados">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer  justify-content-between">
					<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
					<button type="button" class="btn btn-success bi bi-check-square" id="proceso"></button>
				</div>
			</div>	
		</form>
	</div> 
</div>
</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog"  id="modalInsumos">
	<div class="modal-dialog modal-lg modal-servicios" role="document">
		<div class="modal-header modal-servicios-header">
			<h5 class="modal-title"> <i class="zmdi zmdi-accounts"></i> Listado de Insumos</h5>
		</div>
		<div class="modal-content">
			<div class="modal-body modal-servicios-content">
				<div class="row">
					<div class="col-12 col-md-6">
						<input type="text" class="form-control" id="buscarInsumo" placeholder="Buscar insumo...">
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover table-center">
				<thead class="modal-servicios-table-header">
					<tr>
						<th class="hidden-field">Id</th>
						<th class="text-center">Codigo</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Marca</th>
						<th class="text-center">Stock</th>
						<th class="text-center">Stock minimo</th>
						<th class="text-center">Precio</th>
						<th class="text-center">Presentacion</th>
						<th class="text-center">Imagen</th>
					</tr>
				</thead>
				<tbody id="listadoInsumos">
				</tbody>
			</table>
		</div>
		<div class="modal-footer modal-servicios-footer">
			<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
		</div>
	</div>
</div>
<!-- seccion del modal productos -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalEquipos">
	<div class="modal-dialog modal-lg modal-servicios" role="document">
		<div class="modal-header modal-servicios-header">
			<h5 class="modal-title"><i class="bi bi-box-seam-fill"></i> Listado de Equipos</h5>
		</div>
		<div class="modal-content">
			<div class="modal-body modal-servicios-content">
				<div class="row">
					<div class="col-12 col-md-6">
						<input type="text" class="form-control" id="buscarEquipo" placeholder="Buscar equipo...">
					</div>
				</div>
			</div>			
			<table class="table table-striped table-hover table-center">
				<thead class="modal-servicios-table-header">
					<tr>
						<th class="hidden-field">Id</th>
						<th class="text-center">Codigo</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Marca</th>
						<th class="text-center">Modelo</th>
						<th class="text-center">Cantidad</th>
						<th class="text-center">Precio</th>
						<th class="text-center">Imagen</th>
					</tr>
				</thead>
				<tbody id="listadoEquipos"> 
				</tbody>
			</table>
		</div>
		<div class="modal-footer modal-servicios-footer">
			<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
		</div>
	</div>
</div>
<!-- Modal para mostrar detalles de insumos/equipos -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetallesLabel">Detalles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contenidoDetalles">
        <!-- Aquí se cargan los detalles dinámicamente -->
      </div>
    </div>
  </div>
</div>
<!-- Fin modal detalles -->


<div class="modal fade" tabindex="-1" role="dialog" id="modalReporte">
    <div class="modal-dialog modal-smarll" role="document" id="lm">
        <div class="modal-header" id="hm">
            <h5 class="modal-title">Generar Reporte de Servicios</h5>
        </div>
        <div class="modal-content">
            <div class="container" id="mtm">
                <form method="post" id="formReporte" target="_blank">
                    <input type="hidden" name="accion" value="reporte_servicios">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nombre_reporte">Nombre</label>
                            <input class="form-control" type="text" id="nombre_reporte" name="nombre" placeholder="Nombre del servicio..."/>
                        </div>
                        <div class="col-md-6">
                            <label for="descripcion_reporte">Descripción</label>
                            <input class="form-control" type="text" id="descripcion_reporte" name="descripcion" placeholder="Descripción del servicio..."/>
                        </div>
                    </div>
                    <div class="row mt-3">
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
<script type="text/javascript" src="js/servicios.js"></script>
<link href="css/servicios.css" rel="stylesheet" />
</body>
</html>