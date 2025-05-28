<?php 
require_once("comunes/encabezado.php"); 
require_once('comunes/menu.php'); 
?> 
<div class="container"> 
    <br>
    <ul class="nav nav-tabs" id="myTab" role="tablist" >
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="insumos-tab" data-toggle="tab" href="#insumos" role="tab" aria-controls="insumos" aria-selected="true">Insumos</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="entradas-tab" data-toggle="tab" href="#entradas" role="tab" aria-controls="entradas" aria-selected="false">Entradas de Insumos</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="insumos" role="tabpanel" aria-labelledby="insumos-tab">
          <h1>Insumos</h1> 
          <div class="container mt-3">
            <div class="row mt-1 justify-content-center">
                <div class="col-md-2 text-center">
                    <button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Insumo">
                        <i class="bi bi-plus-square"></i>
                    </button>               
                </div>              
            </div>
            <div class="table-responsive" id="tt">
                <table class="table table-striped table-hover table-center" id="tablaInsumos">
                    <thead class="tableh">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Marca</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Stock mínimo</th>
                            <th class="text-center">Presentación</th>       
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
      <h1>Entradas de insumos</h1> 
      <div class="container mt-3">
        <div class="row mt-1 justify-content-center">
            <div class="col-md-2 text-center">
                <button type="button" class="btn-sm btn-success w-75 small-width" id="incluir2" title="Registrar Entrada">
                    <i class="bi bi-plus-square"></i>
                </button>
            </div>              
        </div>
        <div class="table-responsive" id="tt">
            <table class="table table-striped table-hover table-center " id="tablaEntradasInsumos">
                <thead class="tableh">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Insumos</th>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
    <div class="modal-dialog modal-lg" role="document" id="lm">
        <div class="modal-header" id="hm">
            <h5 class="modal-title">Insumo</h5>
        </div>
        <div class="modal-content">
            <div class="container" id="mtm">
                <form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
                    <input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="codigo">Código</label>
                                <input class="form-control" type="text" id="codigo" placeholder="Código obligatorio" title="El codigo del insumo no puede ser modificado..."/>
                                <span id="scodigo"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="nombre">Nombre</label>
                                <input class="form-control" type="text" id="nombre" placeholder="Nombre obligatorio" />
                                <span id="snombre"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="marca">Marca</label>
                                <input class="form-control" type="text" id="marca"/>
                                <span id="smarca"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="stock_total">Stock o existencia</label>
                                <input class="form-control" type="text" id="stock_total" name="stock_total" placeholder="Cantidad existente obligatorio"/>
                                <span id="sstock_total"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="stock_minimo">Stock mínimo</label>
                                <input class="form-control" type="text" id="stock_minimo" name="stock_minimo" placeholder="Stock mínimo obligatorio"/>
                                <span id="sstock_minimo"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="precio">Precio</label>
                                <input class="form-control" type="text" id="precio" placeholder="Obligatorio..."/>
                                <span id="sprecio"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="presentacion">Presentación del producto</label>
                                <select class="form-control select2" id="presentacion" name="presentacion">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                </select>
                                <div class="row mb-3">
                                    <div class="col-4" title="Agregar una Presentación">
                                        <a href="?pagina=presentaciones" ><i class="bi bi-plus-square"></i></a>                        
                                    </div>   
                                </div>                               
                            </div>
                            <div class="col-md-5">
                                <label for="imagen">Imagen del Insumo</label>
                                <input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg" />
                            </div>
                            <div class="col-md-3">
                                <span id="simagen"></span>
                                <img id="imagen_actual" src="" alt="Imagen del insumo" class="img"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
                        <button type="button" class="btn btn-success bi bi-check-square" id="proceso"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog"  id="modal2">
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
<div class="modal fade" tabindex="-1" role="dialog"  id="modalInsumos">
    <div class="modal-dialog modal-lg modal-insumos" role="document">
        <div class="modal-header modal-insumos-header">
            <h5 class="modal-title"><i></i> Listado de Insumos</h5>
        </div>
        <div class="modal-content">
            <div class="modal-body modal-insumos-content">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control" id="buscarInsumo" placeholder="Buscar insumo...">
                    </div>
                </div>
            </div>              
            <table class="table table-striped table-hover table-center">
                <thead class="modal-insumos-table-header">
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
        <div class="modal-footer modal-insumos-footer">
            <button type="button" class="btn" data-dismiss="moda2" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
        </div>
    </div>
</div>
</section>
</section>
<div id="loader" class="loader-container">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>
<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/gestionarInsumos.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>
<script type="text/javascript">
    var esAdmin = <?php echo ($_SESSION['tipo_usuario'] == 'administrador') ? 'true' : 'false'; ?>;
</script>
<script type="text/javascript" src="js/gestionarInsumos.js"></script> 
</body>
</html>