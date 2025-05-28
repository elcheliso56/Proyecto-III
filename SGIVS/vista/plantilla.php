<?php
class plantilla {
    function header() {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>SGIVS - Sistema de Gestión Integral de Ventas y Servicios</title>
            
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            
            <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
            
            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            
            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .navbar {
                    background-color: #28a745;
                }
                .navbar-brand {
                    color: white !important;
                }
                .nav-link {
                    color: rgba(255,255,255,.8) !important;
                }
                .nav-link:hover {
                    color: white !important;
                }
                .card {
                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                    margin-bottom: 1rem;
                }
                .card-header {
                    background-color: #f8f9fa;
                    border-bottom: 1px solid rgba(0,0,0,.125);
                }
                .btn-primary {
                    background-color: #28a745;
                    border-color: #28a745;
                }
                .btn-primary:hover {
                    background-color: #218838;
                    border-color: #1e7e34;
                }
                .btn-success {
                    background-color: #17a2b8;
                    border-color: #17a2b8;
                }
                .btn-success:hover {
                    background-color: #138496;
                    border-color: #117a8b;
                }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-expand-lg navbar-dark mb-4">
                <div class="container-fluid">
                    <a class="navbar-brand" href="index.php">SGIVS</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=calendario">Calendario</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=ingresos">Ingresos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=egresos">Egresos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=productos">Productos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=clientes">Clientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=proveedores">Proveedores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?pagina=logout">Cerrar Sesión</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
        <?php
    }

    function footer() {
        ?>
            </div>
            <footer class="footer mt-auto py-3 bg-light">
                <div class="container text-center">
                    <span class="text-muted">© 2024 SGIVS - Sistema de Gestión Integral de Ventas y Servicios</span>
                </div>
            </footer>
        </body>
        </html>
        <?php
    }
}
?> 