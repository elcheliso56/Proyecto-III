<?php 
require_once("comunes/encabezado.php"); //Incluye el encabezado común 
require_once('comunes/menu.php'); //Incluye el menú común 
?> 

<div class="container">
    <h1><i class="zmdi zmdi-account-circle" style="color: black;"></i> Editar Perfil</h1> 

    <div class="modal-dialog modal-lg" role="document"> <!-- Diálogo modal para editar perfil -->
        <div class="modal-content">
            <div class="container" id="mtm">  
                <form method="post" id="f" autocomplete="off" enctype="multipart/form-data"> <!-- Formulario para editar perfil -->
                    <input autocomplete="off" type="text" class="form-control" name="accion" id="accion"> <!-- Campo oculto para la acción -->
                    <div class="container"> 
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="usuario">Usuario</label>
                                <input class="form-control" type="text" id="usuario"/> 
                            </div>
                            <div class="col-md-4">
                                <label for="nombre_apellido">Nombre y Apellido</label>
                                <input class="form-control" type="text" id="nombre_apellido"/> 
                            </div>
                            <div class="col-md-4">
                                <label for="id_rol">Rol</label>
                                <input class="form-control" type="text" id="id_rol"/> 
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6" title="Solo si desea actualizar la imagen...">
                                <label for="imagen">Imagen del Usuario</label>
                                <input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg" /> <!-- Campo para subir imagen -->                  
                            </div>
                            <div class="col-md-6">
                                <span id="simagen"></span> <!-- Mensaje de error para imagen -->
                                <img id="imagen_actual" src="" alt="Imagen del usuario" class="img"/><!-- Imagen actual del usuario -->
                            </div>
                        </div>
                        <div class="modal-footer bg-light justify-content-center">
                            <button type="button" class="btn btn-info bi bi-check-circle" id="proceso"> MODIFICAR</button> <!-- Botón para modificar perfil -->
                        </div>
                    </div>  
                </form>
            </div> 
        </div>
    </div>
</div> 
</section>
</section>
<script type="text/javascript" src="js/perfil.js"></script> <!-- Script para manejar la lógica del perfil -->
</body>
</html>