<?php 
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 
<div class="container">
	<h1><i class="bi bi-file-text-fill"></i> Reportes</h1> 
	<div class="container" style="border: 1px solid grey;"> 
		<hr/>   
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Insumos</h4>
				<div class="row">
					<div class="col-12 col-lg-2">
						<label for="codigo">Código</label>
						<input class="form-control" type="text" id="codigo" name="codigo" placeholder="Código del insumo..."/>
						<span id="scodigo"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre del insumo..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="marca">Marca</label>
						<input class="form-control" type="text" id="marca" name="marca" placeholder="Marca del insumo..."/>
						<span id="smarca"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="stock_total">Stock total</label>
						<input class="form-control" type="text" id="stock_total" name="stock_total" placeholder="Stock total..."/>
						<span id="sstock_total"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="stock_minimo">Stock mínimo</label>
						<input class="form-control" type="text" id="stock_minimo" name="stock_minimo" placeholder="Stock mínimo..."/>
						<span id="sstock_minimo"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="precio">Precio</label>
						<input class="form-control" type="text" id="precio" name="precio" placeholder="Precio..."/>
						<span id="sprecio"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="presentacion">Presentación</label>
						<input class="form-control" type="text" id="presentacion" name="presentacion" placeholder="Presentación..."/>
						<span id="spresentacion"></span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_insumos" name="reporte_insumos">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>   
		<hr/>   
	</div>
	<div class="container" style="border: 1px solid grey;"> 
		<hr/>   
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Equipos</h4>
				<div class="row">
					<div class="col-12 col-lg-2">
						<label for="codigo">Código</label>
						<input class="form-control" type="text" id="codigo" name="codigo" placeholder="Código del equipo..."/>
						<span id="scodigo"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre del equipo..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="marca">Marca</label>
						<input class="form-control" type="text" id="marca" name="marca" placeholder="Marca del equipo..."/>
						<span id="smarca"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="modelo">Modelo</label>
						<input class="form-control" type="text" id="stock_total" name="modelo" placeholder="Modelo del equipo..."/>
						<span id="smodelo"></span>
					</div>
					<div class="col-12 col-lg-2">
						<label for="cantidad">Cantidad</label>
						<input class="form-control" type="text" id="stock_minimo" name="cantidad" placeholder="Cantidad del equipo..."/>
						<span id="scantidad"></span>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_equipos" name="reporte_equipos">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>   
		<hr/>   
	</div>
	<div class="container" style="border: 1px solid grey;"> 
		<hr/>		
		<form method="post" action="" id="f" target="_blank">
			<div class="container">
				<h4>Reporte de Usuarios</h4>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="cedula">Cedula</label>
						<input class="form-control" type="text" id="cedula" name="cedula"  placeholder="Numero de cedula..."/>
						<span id="scedula"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="nombre">Nombre</label>
						<input class="form-control" type="text" id="nombre" name="nombre"  placeholder="Nombre del usuario..."/>
						<span id="snombre"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="apellido">Apellido</label>
						<input class="form-control" type="text" id="apellido" name="apellido"  placeholder="Apellido del usuario..."/>
						<span id="sapellido"></span>
					</div>					
				</div>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="correo">Correo</label>
						<input class="form-control" type="text" id="correo" name="correo"  placeholder="Correo del usuario..."/>
						<span id="scorreo"></span>
					</div>					
					<div class="col-12 col-lg-4">
						<label for="telefono">Teléfono</label>
						<input class="form-control" type="text" id="telefono" name="telefono"  placeholder="teléfono del usuario..."/>
						<span id="stelefono"></span>
					</div>
					<div class="col-12 col-lg-4">
						<label for="nombre_usuario">Nombre de usuario</label>
						<input class="form-control" type="text" id="nombre_usuario" name="nombre_usuario"  placeholder="Nombre de usuario..."/>
						<span id="snombre_usuario"></span>
					</div>										
				</div>
				<div class="row">
					<div class="col-12 col-lg-4">
						<label for="tipo_usuario">Tipo de usuario</label>
						<select class="form-control" id="tipo_usuario" name="tipo_usuario" >
							<option value="" selected>Todo tipo de usuario</option>
							<option value="administrador">Administrador</option>
							<option value="usuario">Usuario</option>										
						</select>
						<span id="stipo_usuario"></span>
					</div>
				</div>				
				<br>
				<div class="row">
					<div class="col-7 col-lg-4">
						<button type="submit" class="btn btn-warning" id="reporte_usuarios" name="reporte_usuarios">GENERAR PDF</button>
					</div>
				</div>
			</div>
		</form>	
		<hr/>	
	</div> 	  
</div>
</section>
</section>
</body>
</html>