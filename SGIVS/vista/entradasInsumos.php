<?php 
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?>
<div class="container"> 
	<h1> Gestionar Entradas de Insumos</h1> 
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Entrada"><i class="bi bi-plus-square"></i></button>
			</div>				
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablaEntradasInsumos">
				<thead class="tableh">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Insumos</th>
						<th class="text-center">Total</th>
					</tr>
				</thead>
				<tbody id="resultadoconsulta">			  
				</tbody>
			</table>
		</div>
	</div>
</div> 
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class=" modal-dialog modal-xl" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i></i> Entrada de Insumos</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	
						<div class="row">
							<div class="col-md-12 input-group">
								<input class="form-control" type="text" id="codigoInsumos" name="codigoInsumos" placeholder="Codigo de insumo..."  />
								<button type="button" class="btn btn-primary" id="listadoDeInsumos" name="listadoDeInsumos"  title="Listado de insumos"><i class='bi bi-search'></i> Buscar Insumo</button>
							</div>
						</div>
						<br>
						<div class="row">
							<h5><strong>Insumos seleccionados</strong></h5>
							<div class="table-responsive" id="tt">
								<table class="table table-striped table-hover table-center ">
									<thead class="tableh">
										<tr>
											<th class="text-center" >Codigo</th>
											<th class="text-center" >Nombre</th>
											<th class="text-center" >Cantidad</th>
											<th class="text-center" >Precio de compra</th>
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
</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog"  id="modalInsumos">
	<div class="modal-dialog modal-lg" role="document" style="border: 1px solid black;">
		<div class="modal-header text-light" style="border: 1px solid black; 	background-color: #14345a; ">
			<h5 class="modal-title"><i></i> Listado de Insumos</h5>
		</div>
		<div class="modal-content" class="table-responsive" id="tt">
			<div class="modal-body" style="background-color: #14345a; ">
				<div class="row">
					<div class="col-12 col-md-6">
						<input type="text" class="form-control" id="buscarInsumo" placeholder="Buscar insumo...">
					</div>
				</div>
			</div>				
			<table class="table table-striped table-hover table-center">
				<thead style="background-color: #f1c40f;">
					<tr>
						<th style="display:none">Id</th>
						<th class="text-center">Código</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Marca</th>
						<th class="text-center">Stock</th>
						<th class="text-center">Stock mínimo</th>
						<th class="text-center">Precio</th>
						<th class="text-center">Presentación</th>
						<th class="text-center">Imagen</th>
					</tr>
				</thead>
				<tbody id="listadoInsumos"> 
				</tbody>
			</table>
		</div>
		<div class="modal-footer bg-light">
			<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
		</div>
	</div>
</div>
</section>
</section>
<script type="text/javascript" src="js/entradasInsumos.js"></script>
<div id="loader" class="loader-container">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>