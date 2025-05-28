<?php
require_once("comunes/encabezado.php"); //Incluye el encabezado común de la página 
require_once('comunes/menu.php'); //Incluye el menú común de la página 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
<br>
<div class="container">
    <h1>Gestionar Consultas</h1> <!-- Título principal de la página -->
    <!-- Contenedor para el botón de agregar nuevo cliente -->
    <div class="container">
        <div class="row mt-1 justify-content-center">
            <div class="col-md-2 text-center">
                <!-- Botón para registrar un nuevo cliente -->
                <button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Consulta"><i class="bi bi-plus-square"></i></button>
            </div>
        </div>
    </div>
    <!-- Contenedor para la tabla de clientes -->
    <div class="container">
        <div class="table-responsive" id="">
            <!-- Tabla para mostrar la lista de clientes -->
            <table class="table table-striped table-hover table-center" id="tablacliente">
                <thead>
                    <tr>
                        <!-- Encabezados de la tabla -->
                        <th class="text-center">#</th>
                        <th class="text-center">Documento</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Apellido</th>
                        <th class="text-center">Telefono</th>
                        <th class="text-center">Tratamientos</th>
                        <th class="text-center">Fecha Consulta</th>
                        <th class="text-center">Doctor</th>
                    </tr>

                </thead>

                <tbody id="resultadoconsulta">
                    <!-- Aquí se cargarán dinámicamente los datos de los clientes -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal para agregar o editar clientes -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal1">
        <div class="modal-dialog modal-lg" role="document" id="lm">
            <div class="modal-header" id="hm">
                <h5 class="modal-title"><i class="zmdi zmdi-accounts"></i> Servicios</h5>
            </div>
            <div class="modal-content">
                <div class="container" id="mtm">
                    <form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
                        <input autocomplete="off" type="text" class="form-control" name="accion" id="accion">

                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cedula" class="form-label"></label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <button class="btn btn-outline-info flex-shrink-0" type="button" id="listadopaciente" name="listadopaciente" title="Listado de Cédulas">
                                                <i class="bi bi-card-list"></i>
                                            </button>
                                            <input
                                                type="text"
                                                class="form-control flex-grow-1"
                                                id="cedula"
                                                name="cedula"
                                                placeholder="Cédula"
                                                aria-label="Cédula"
                                                disabled>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <label for="telefono">Teléfono</label>
                                    <input class="form-control" type="text" id="telefono" name="telefono" placeholder="Ejemplo: 04123456789" disabled />
                                    <span id="stelefono"></span>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre">Nombre</label>
                                    <input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre obligatorio" disabled />
                                    <span id="snombre"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="Apellido">Apellido</label>
                                    <input class="form-control" type="text" id="Apellido" name="Apellido" placeholder="Apellido obligatorio" disabled />
                                    <span id="sApellido"></span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label for="tratamiento" class="form-label">Tratamiento Realizado (descripción libre)</label>
                                    <textarea class="form-control" id="tratamiento" name="tratamiento" rows="4" placeholder="Describa el tratamiento. Ej: 1 Restauración de resina en diente 3.6, 1 Extracción de cordal inferior."></textarea>
                                    <span class="text-danger" id="stratamiento"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-9">
                                    <label for="select_tratamiento_add" class="form-label">Añadir de la lista</label>
                                    <select class="form-select" id="select_tratamiento_add" style="width: 100%;">
                                        <option value="">Buscar y añadir tratamiento de la lista...</option>
                                        <optgroup label="ORTODONCIA">
                                            <option value="INST. * LIMPIEZA">INST. * LIMPIEZA</option>
                                            <option value="MEDIA INSTALACION">MEDIA INSTALACION</option>
                                            <option value="DESINTAJACION">DESINTAJACION</option>
                                            <option value="CONTROL">CONTROL</option>
                                            <option value="TUBOS">TUBOS</option>
                                            <option value="REPOSICION BRACKETS">REPOSICION BRACKETS</option>
                                            <option value="RESORTE">RESORTE</option>
                                            <option value="BOTTONES">BOTTONES</option>
                                            <option value="TOPE 1">TOPE 1</option>
                                            <option value="TOPE 2">TOPE 2</option>
                                            <option value="LIGAS INTERMAXILARES">LIGAS INTERMAXILARES</option>
                                        </optgroup>
                                        <optgroup label="RX (Radiografías)">
                                            <option value="PENAPICAL">PENAPICAL</option>
                                            <option value="PERIAPICAL MILIMETRADA">PERIAPICAL MILIMETRADA</option>
                                            <option value="BITEWING">BITEWING</option>
                                            <option value="A.T.M">A.T.M</option>
                                            <option value="SENOS MAXILARES">SENOS MAXILARES</option>
                                            <option value="CEFALICA LATERAL">CEFALICA LATERAL</option>
                                            <option value="CEFALICA OBLICUA">CEFALICA OBLICUA</option>
                                            <option value="CEFALICA P.A">CEFALICA P.A</option>
                                        </optgroup>
                                        <optgroup label="GENERALES">
                                            <option value="CONSULTA">CONSULTA</option>
                                            <option value="LIMPIEZA SIMPLE">LIMPIEZA SIMPLE</option>
                                            <option value="LIMPIEZA PROFUNDA">LIMPIEZA PROFUNDA</option>
                                            <option value="PROVISIONAL">PROVISIONAL</option>
                                            <option value="RESTAURACION 1">RESTAURACION 1</option>
                                            <option value="RESTAURACION 2">RESTAURACION 2</option>
                                            <option value="RESTAURACION 3">RESTAURACION 3</option>
                                            <option value="RESTAURACION 4">RESTAURACION 4</option>
                                            <option value="RESTAURACION 5">RESTAURACION 5</option>
                                            <option value="CARILLA O RECONSTRUCCIÓN">CARILLA O RECONSTRUCCIÓN</option>
                                            <option value="EXTRACCIÓN DE LECHE O CON MOVILIDAD">EXTRACCIÓN DE LECHE O CON MOVILIDAD</option>
                                            <option value="EXTRACCIONES (1)">EXTRACCIONES (1)</option>
                                            <option value="EXTRACCIONES (2)">EXTRACCIONES (2)</option>
                                            <option value="EXTRACCIONES (3)">EXTRACCIONES (3)</option>
                                            <option value="EXTRACCIONES (4)">EXTRACCIONES (4)</option>
                                            <option value="PROTEST">PROTEST</option>
                                            <option value="DISEÑO DE SONRISA">DISEÑO DE SONRISA</option>
                                            <option value="FERULA DE BRUXIMO">FERULA DE BRUXIMO</option>
                                            <option value="BLANQUEAMIENTO">BLANQUEAMIENTO</option>
                                        </optgroup>
                                        <optgroup label="ENDODONCIA">
                                            <option value="MULTI (MUELAS)">MULTI (MUELAS)</option>
                                            <option value="MONO V (B) CENTRALES Y PREMOLARES">MONO V (B) CENTRALES Y PREMOLARES</option>
                                            <option value="RETRATAMIENTO DE CONDUCTO">RETRATAMIENTO DE CONDUCTO</option>
                                        </optgroup>
                                        <optgroup label="CIRUJANO MAXILOFACIAL">
                                            <option value="EXTRACCIONES DE CORDALES SIMPLES (1)">EXTRACCIONES DE CORDALES SIMPLES (1)</option>
                                            <option value="EXTRACCIONES DE CORDALES SIMPLES (2)">EXTRACCIONES DE CORDALES SIMPLES (2)</option>
                                            <option value="EXTRACCIONES DE CORDALES COMPLEJAS (1)">EXTRACCIONES DE CORDALES COMPLEJAS (1)</option>
                                            <option value="EXTRACCIONES DE CORDALES COMPLEJAS (2)">EXTRACCIONES DE CORDALES COMPLEJAS (2)</option>
                                            <option value="CANINOS O DIENTES RETENIDOS">CANINOS O DIENTES RETENIDOS</option>
                                            <option value="SEDACIÓN">SEDACIÓN</option>
                                            <option value="UDPAPAPADA">UDPAPAPADA</option>
                                            <option value="BICHEPTOMIA">BICHEPTOMIA</option>
                                            <option value="LESIONES DE TEJIDO BLANCO">LESIONES DE TEJIDO BLANCO</option>
                                            <option value="REGULARISACIÓN OSIA">REGULARISACIÓN OSIA</option>
                                            <option value="TORUS">TORUS</option>
                                        </optgroup>
                                        <optgroup label="ODONTOPEDIATRA">
                                            <option value="CONSULTA CON PROFILAXIS">CONSULTA CON PROFILAXIS</option>
                                            <option value="RESTAURACION (1)">RESTAURACION (1)</option>
                                            <option value="RESTAURACION (2)">RESTAURACION (2)</option>
                                            <option value="CORONAS ANTERIORES">CORONAS ANTERIORES</option>
                                            <option value="TERAPIAS PULPARES (1)">TERAPIAS PULPARES (1)</option>
                                            <option value="TERAPIAS PULPARES (2)">TERAPIAS PULPARES (2)</option>
                                            <option value="EKODONCIA">EKODONCIA</option>
                                            <option value="OJAL QUIRURJICO">OJAL QUIRURJICO</option>
                                            <option value="EMERGENCIA POR TRAUMATISMO A PARTIR DE:">EMERGENCIA POR TRAUMATISMO A PARTIR DE:</option>
                                            <option value="SELLANTES">SELLANTES</option>
                                            <option value="MANTENEDOR DE ESPACIOS A PARTIR DE:">MANTENEDOR DE ESPACIOS A PARTIR DE:</option>
                                            <option value="APAROTOLOGIA REMOVIBLE A PARTIR DE:">APAROTOLOGIA REMOVIBLE A PARTIR DE:</option>
                                            <option value="INCRUSTACIONES PARA MOLARES CON HIM">INCRUSTACIONES PARA MOLARES CON HIM</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-info w-100" id="btn_add_tratamiento"><i class="bi bi-plus-square"></i> Añadir</button>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="listadoc" name="listadoc" title="Listado de Doctores">
                                    <i class="bi bi-card-list"></i>
                                </button>
                            </div>
                            <div class="col-md-5 d-flex align-items-end">
                                <input class="form-control me-2" type="text" id="doctor" name="doctor" placeholder="Doctor" />
                                <span id="sdoctor"></span>
                            </div>
                            <div class="col-md-5">
                                <label for="fechaconsulta">fecha de consulta</label>
                                <input class="form-control" type="date" id="fechaconsulta" name="fechaconsulta" placeholder="la fecha es obligatorio" />
                                <span id="sfechaconsulta"></span>
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
<!-- Modal de pacintes -->
<div class="modal fade" id="modalpaciente" tabindex="-1">
    <div class="modal-dialog modal-lg" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Pacientes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="">
                    <!-- Tabla para mostrar la lista de clientes -->
                    <table class="table table-striped-columns table-hover">
                        <thead>
                            <tr>
                                <!-- Encabezados de la tabla -->
                                <th class="text-center">cedula</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Apellido</th>
                                <th class="text-center">Telefono</th>
                            </tr>
                        </thead>
                        <tbody id="tablapaciente">
                            <!-- Aquí se cargarán dinámicamente los datos de los clientes -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
    </div>

</div>


<div class="modal fade" id="modaldoc" tabindex="-1">
    <div class="modal-dialog modal-lg" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Doctor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="">
                    <!-- Tabla para mostrar la lista de clientes -->
                    <table class="table table-striped-columns table-hover">
                        <thead>
                            <tr>
                                <!-- Encabezados de la tabla -->
                                <th class="text-center">Nombres</th>
                            </tr>
                        </thead>
                        <tbody id="tabladoc">
                            <!-- Aquí se cargarán dinámicamente los datos de los clientes -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
           
        </div>
    </div>

</div>




<script type="text/javascript" src="js/consultas.js"></script><!-- Inclusión del archivo JavaScript de clientes -->
<div id="loader" class="loader-container" style="display: none;">
    <div class="loader"></div>
    <p>Procesando solicitud...</p>
</div>
</body>

</html>