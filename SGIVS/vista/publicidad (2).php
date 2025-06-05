<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="otros/img/iconos/icono.ico">
    <title>Clinica Odontologica Vital Sonrisa - Tu Sonrisa, Nuestra Pasión</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container">
          <a class="navbar-brand" href="#hero"><img src="assets/img/vital.png" alt="Logo Clínica" class="navbar-logo me-2">Clinica Odontologica Vital Sonrisa</a>               
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#hero">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#why-us">¿Por qué elegirnos?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light nav-link-custom" href="?pagina=login">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="hero" class="d-flex align-items-center justify-content-center text-center">
        <div class="container">
            <h1 class="display-3 animate__animated animate__fadeInDown">Tu Sonrisa, Nuestra Pasión</h1>
            <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Expertos en salud dental para toda la familia.</p>
            <button type="button" class="btn btn-primary btn-lg animate__animated animate__zoomIn animate__delay-2s" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                Agenda tu Consulta
            </button>           
    </section>    
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 animate__animated animate__fadeInLeft">
                    <img src="assets/img/dent-equip.jpg" alt="Equipo de Dentistas" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6 animate__animated animate__fadeInRight">
                    <h2 class="display-4 mb-4">Sobre Nosotros</h2>
                    <p class="lead">En la Clinica Odontologica Vital Sonrisa, nos dedicamos a brindar atención dental de la más alta calidad en un ambiente cómodo y amigable.</p>
                    <p>Nuestro equipo de profesionales altamente calificados utiliza tecnología de vanguardia para asegurar diagnósticos precisos y tratamientos efectivos para todas las edades. Creemos que una sonrisa sana es una sonrisa feliz.</p>
                    <a href="#contact" class="btn btn-dark btn-lg mt-3 animate__animated animate__pulse animate__infinite">Contáctanos</a>
                </div>
            </div>
        </div>
    </section>
    <section id="services" class="py-5 text-center">
        <div class="container">
            <h2 class="display-4 mb-5 animate__animated animate__fadeIn">Nuestros Servicios</h2>
            <div class="row">
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp">
                    <div class="card service-card">
                        <div class="card-body">
                            <img src="assets/icons/local_hospital.png" alt="Odontología General" class="service-icon mb-3">
                            <h5 class="card-title">Odontología General</h5>
                            <p class="card-text">Check-ups, limpiezas y empastes para una salud oral óptima.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="card service-card">
                        <div class="card-body">
                            <img src="assets/icons/star.png" alt="Estética Dental" class="service-icon mb-3">
                            <h5 class="card-title">Estética Dental</h5>
                            <p class="card-text">Blanqueamientos, carillas y coronas para una sonrisa deslumbrante.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="card service-card">
                        <div class="card-body">
                            <img src="assets/icons/child_care.png" alt="Odontopediatría" class="service-icon mb-3">
                            <h5 class="card-title">Odontopediatría</h5>
                            <p class="card-text">Atención dental especializada y amigable para los más pequeños.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp">
                    <div class="card service-card">
                        <div class="card-body">
                            <img src="assets/icons/dentistry.png" alt="Ortodoncia" class="service-icon mb-3">
                            <h5 class="card-title">Ortodoncia</h5>
                            <p class="card-text">Corrección de la posición de los dientes para una mordida perfecta.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="card service-card">
                        <div class="card-body">
                            <img src="assets/icons/healing.png" alt="Implantes Dentales" class="service-icon mb-3">
                            <h5 class="card-title">Implantes Dentales</h5>
                            <p class="card-text">Soluciones duraderas para dientes perdidos, restaurando tu sonrisa.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="card service-card">
                        <div class="card-body">
                            <img src="assets/icons/medication.png" alt="Endodoncia" class="service-icon mb-3">
                            <h5 class="card-title">Endodoncia</h5>
                            <p class="card-text">Tratamientos de conducto para salvar dientes dañados o infectados.</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </section>
    <section id="why-us" class="py-5 bg-light text-center">
        <div class="container">
            <h2 class="display-4 mb-5 animate__animated animate__fadeIn">¿Por qué elegirnos?</h2>
            <div class="row">
                <div class="col-md-4 mb-4 animate__animated animate__zoomIn">
                    <img src="assets/icons/favorite.png" alt="Atención Personalizada" class="why-us-icon mb-3">
                    <h3>Atención Personalizada</h3>
                    <p>Cada paciente es único y recibe un plan de tratamiento adaptado a sus necesidades.</p>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__zoomIn animate__delay-1s">
                    <img src="assets/icons/thumb_up.png" alt="Profesionales Expertos" class="why-us-icon mb-3">
                    <h3>Profesionales Expertos</h3>
                    <p>Contamos con un equipo de odontólogos altamente calificados y en constante formación.</p>
                </div>
                <div class="col-md-4 mb-4 animate__animated animate__zoomIn animate__delay-2s">
                    <img src="assets/icons/emoji_objects.png" alt="Tecnología de Vanguardia" class="why-us-icon mb-3">
                    <h3>Tecnología de Vanguardia</h3>
                    <p>Equipos modernos para diagnósticos precisos y tratamientos eficientes e indoloros.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="contact" class="py-5 text-center bg-primary text-white">
        <div class="container">
            <h2 class="display-4 mb-4 animate__animated animate__fadeIn">Agenda tu Consulta</h2>
            <p class="lead mb-4 animate__animated animate__fadeInUp">¿Listo para transformar tu sonrisa? Contáctanos hoy mismo.</p>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center flex-wrap">
                <a href="tel:+584141570548" class="btn btn-red btn-lg animate__animated animate__pulse animate__infinite">
                    <img src="assets/icons/phone.png" alt="Teléfono" class="contact-icon me-2"> Llamar Ahora
                </a>

                <div class="dropdown">
                    <button class="btn btn-success btn-lg dropdown-toggle animate__animated animate__pulse animate__infinite" type="button" id="dropdownWhatsApp" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="assets/icons/whatsapp.svg" alt="WhatsApp" class="contact-icon me-2"> WhatsApp
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownWhatsApp">
                        <li><a class="dropdown-item" href="https://wa.me/+584141570548?text=Hola%20quiero%20agendar%20una%20cita.
                            " target="_blank">Enviar Mensaje</a></li>
                        <li><a class="dropdown-item" href="https://wa.me/+584141570548" target="_blank">Llamar por WhatsApp</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">              
            <p><strong><h6>Dirección: </h6></strong> Calle 39 entre Carreras 20 y 21, Edificio La Princesa, Piso 1, Consultorio 7 / Barquisimeto</p>    
            <p><strong><h6>Horarios de Atención: </h6></strong> De Lunes a Viernes de 8 AM a 6 PM Sábados de 8 am a 1 pm</p>
            <p><strong><h6>Teléfono: </h6></strong><a href="tel:+584141570548" class="text-white">+58 414-1570548</a></p>
            <p>&copy; 2025 Clinica Odontologica Vital Sonrisa. Todos los derechos reservados.</p>
            <div class="mt-3 d-flex justify-content-center align-items-center">
                <a href="https://www.google.com/maps/place/Consultorio+Odontologico+Vital+Sonrisa/@10.0675026,-69.3302743,18.25z/data=!4m6!3m5!1s0x8e876708d2768057:0x92e5e5e934d05622!8m2!3d10.0665452!4d-69.3297747!16s%2Fg%2F11l59wn96l?hl=es&entry=ttu&g_ep=EgoyMDI1MDUyNy4wIKXMDSoASAFQAw%3D%3D" class="text-white mx-2 instagram-link" target="_blank">
                    <img src="assets/img/google.png" alt="Instagram" class="instagram-icon me-2">
                    Ver la dirección en Google Maps
                </a>
            </div>


            <div class="mt-3 d-flex justify-content-center align-items-center">
                <a href="https://www.instagram.com/vitalsonrisa.bqto?igsh=MW14bXprejF5YzR1aQ==" class="text-white mx-2 instagram-link" target="_blank" >
                    


                    <img src="assets/img/media.png" alt="Instagram" class="instagram-icon me-2">
                    Síguenos en Instagram
                </a>
            </div>
    </footer>
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Agenda tu Consulta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   required
                                   pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                   maxlength="16"
                                   title="Solo letras y espacios, máximo 16 caracteres."
                                   placeholder="Tu nombre">
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido"
                                   required
                                   pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                   maxlength="16"
                                   title="Solo letras y espacios, máximo 16 caracteres."
                                   placeholder="Tu apellido">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <div class="input-group">
                                <span class="input-group-text">+58</span>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                    required
                                    pattern="[0-9]{10}"
                                    maxlength="10"
                                    title="Ingresa 10 dígitos numéricos (ej. 4121234567)"
                                    placeholder="Tu número telefónico (ej. 4121234567)">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo de Contacto:</label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="4"
                                      required
                                      placeholder="Explícanos brevemente tu motivo de contacto..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="responseModalHeader">
                    <h5 class="modal-title" id="responseModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="responseModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div id="loader" class="loader-container" style="display: none;">
        <div class="loader"></div>
        <p>Procesando solicitud...</p>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="js/publicidad.js"></script>
</body>
</html>