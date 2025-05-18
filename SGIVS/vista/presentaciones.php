<?php 
require_once("comunes/encabezado.php");
require_once('comunes/menu.php');
?> 
<div class="container"> 
	<h1> Gestionar Presentaciones</h1>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Presentacion"><i class="bi bi-plus-square"></i></button>
			</div>				
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablaPresentaciones">
				<thead class="tableh">
					<tr>
						<th class="text-center">#</th>	
						<th class="text-center">Nombre</th>
						<th class="text-center">Descripción</th>
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
			<h5 class="modal-title"><i></i> Presentacion</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	
						<div class="row mb-3" >
							<div class="col-md-6">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre obligatorio"/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-6">
								<label for="descripcion">Descripción</label>
								<input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción de la presentacion" />
								<span id="sdescripcion"></span>
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
</section>
</section>
<script type="text/javascript" src="js/presentaciones.js"></script>
<div id="loader" class="loader-container">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>