<?php 
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 
<div class="container"> 
	<h1> Gestionar Equipos</h1> 
	
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Equipo">
					<i class="bi bi-plus-square"></i>
				</button>			   
			</div>				
		</div>
	</div>
	<div class="container">
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
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1"> 
	<div class="modal-dialog modal-lg" role="document"id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="bi bi-box-seam-fill" style="color: red;"></i> Equipo</h5> 
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
							<div class="col-md-5">
								<label for="imagen">Imagen del equipo</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen"accept=".png,.jpg,.jpeg" />
							</div>
							<div class="col-md-3">
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
</section>
</section>
<script type="text/javascript" src="js/equipos.js"></script> 
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>