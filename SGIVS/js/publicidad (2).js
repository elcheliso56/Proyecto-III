// JavaScript para la navbar que se esconde/aparece
const mainNavbar = document.getElementById('mainNavbar');
const heroSection = document.getElementById('hero');
const showNavbarThreshold = 100; // Distancia desde el top para mostrar la navbar en scroll

function hideNavbar() {
    mainNavbar.style.transform = 'translateY(-100%)';
    mainNavbar.style.transition = 'transform 0.4s ease-out';
}

function showNavbar() {
    mainNavbar.style.transform = 'translateY(0)';
    mainNavbar.style.transition = 'transform 0.3s ease-in';
}

if (window.scrollY > 0) {
     hideNavbar();
} else {
     showNavbar(); // Mostrar si estamos en el top
}

let lastScrollY = window.scrollY;
window.addEventListener('scroll', () => {
    if (window.scrollY < lastScrollY) {
        showNavbar();
    } else if (window.scrollY > lastScrollY && window.scrollY > mainNavbar.offsetHeight + 50) {
        hideNavbar();
    }
    lastScrollY = window.scrollY;

    if (window.scrollY < 50) {
        showNavbar();
    }
});

let hoverTimeout;
const hoverAreaHeight = 80;

window.addEventListener('mousemove', (e) => {
    if (e.clientY < hoverAreaHeight) {
        clearTimeout(hoverTimeout);
        showNavbar();
    } else {
        if (window.scrollY > 50) {
            hoverTimeout = setTimeout(hideNavbar, 500);
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const navbarHeight = mainNavbar.offsetHeight;
    document.querySelectorAll('section').forEach(section => {
        if (section.id !== 'hero') {
            section.style.paddingTop = `${navbarHeight + 30}px`;
        }
    });
});

// Script para oscurecer el fondo cuando el modal de cita esté abierto
const appointmentModal = document.getElementById('appointmentModal');
appointmentModal.addEventListener('show.bs.modal', function () {
    document.body.classList.add('modal-open-overlay');
});
appointmentModal.addEventListener('hide.bs.modal', function () {
    document.body.classList.remove('modal-open-overlay');
});

// Script para mostrar el modal de respuesta
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const msg = urlParams.get('msg');

    if (status && msg) {
        // Ocultar el modal de cita si está abierto para evitar solapamiento
        const appointmentModalEl = document.getElementById('appointmentModal');
        const bsAppointmentModal = bootstrap.Modal.getInstance(appointmentModalEl); 
        if (bsAppointmentModal) {
            bsAppointmentModal.hide(); 
        }

        const responseModalElement = document.getElementById('responseModal');
        const responseModal = new bootstrap.Modal(responseModalElement);
        const modalHeader = document.getElementById('responseModalHeader');
        const modalTitle = document.getElementById('responseModalLabel');
        const modalMessage = document.getElementById('responseModalMessage');

        modalMessage.innerHTML = decodeURIComponent(msg);

        // Configurar el título y color del encabezado del modal según el estado
        if (status === 'success') {
            modalTitle.textContent = '¡Éxito!';
            modalHeader.style.backgroundColor = '#007bff';
            modalHeader.style.borderColor = '#007bff';
            modalHeader.style.color = '#ffffff';
        } else if (status === 'error') {
            modalTitle.textContent = 'Error';
            modalHeader.style.backgroundColor = '#dc3545';
            modalHeader.style.borderColor = '#dc3545';
            modalHeader.style.color = '#ffffff';
        } else {
            modalTitle.textContent = 'Mensaje';
            modalHeader.style.backgroundColor = '#6c757d';
            modalHeader.style.borderColor = '#6c757d';
            modalHeader.style.color = '#ffffff';
        }

        responseModal.show();

        // Limpiar los parámetros de la URL después de mostrar el modal
        history.replaceState({}, document.title, window.location.pathname + window.location.search.replace(/&status=[^&]*|&msg=[^&]*/g, '').replace(/\?$/, ''));
    }
});

// Funciones de validación
function validarNombre(nombre) {
    const regex = /^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/;
    if (!nombre) {
        return "El campo Nombre es obligatorio.";
    }
    if (!regex.test(nombre)) {
        return "El Nombre solo debe contener letras y espacios.";
    }
    if (nombre.length > 16) {
        return "El Nombre no debe exceder los 16 caracteres.";
    }
    return "";
}

function validarApellido(apellido) {
    const regex = /^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/;
    if (!apellido) {
        return "El campo Apellido es obligatorio.";
    }
    if (!regex.test(apellido)) {
        return "El Apellido solo debe contener letras y espacios.";
    }
    if (apellido.length > 16) {
        return "El Apellido no debe exceder los 16 caracteres.";
    }
    return "";
}

function validarTelefono(telefono) {
    const regex = /^[0-9]{10}$/;
    if (!telefono) {
        return "El campo Teléfono es obligatorio.";
    }
    if (!regex.test(telefono)) {
        return "El número telefónico debe tener exactamente 10 dígitos numéricos.";
    }
    return "";
}

function validarMotivo(motivo) {
    if (!motivo.trim()) {
        return "El campo Motivo de Contacto es obligatorio.";
    }
    if (motivo.length < 10) {
        return "El motivo debe tener al menos 10 caracteres.";
    }
    if (motivo.length > 500) {
        return "El motivo no debe exceder los 500 caracteres.";
    }
    return "";
}

function mostrarError(input, mensaje) {
    const formGroup = input.closest('.mb-3');
    let errorDiv = formGroup.querySelector('.invalid-feedback');
    
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        formGroup.appendChild(errorDiv);
    }
    
    errorDiv.textContent = mensaje;
    input.classList.add('is-invalid');
}

function limpiarError(input) {
    const formGroup = input.closest('.mb-3');
    const errorDiv = formGroup.querySelector('.invalid-feedback');
    
    if (errorDiv) {
        errorDiv.textContent = '';
    }
    input.classList.remove('is-invalid');
}

function validarFormulario() {
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const motivo = document.getElementById('motivo').value.trim();
    
    let hayErrores = false;
    
    // Validar nombre
    const errorNombre = validarNombre(nombre);
    if (errorNombre) {
        mostrarError(document.getElementById('nombre'), errorNombre);
        hayErrores = true;
    } else {
        limpiarError(document.getElementById('nombre'));
    }
    
    // Validar apellido
    const errorApellido = validarApellido(apellido);
    if (errorApellido) {
        mostrarError(document.getElementById('apellido'), errorApellido);
        hayErrores = true;
    } else {
        limpiarError(document.getElementById('apellido'));
    }
    
    // Validar teléfono
    const errorTelefono = validarTelefono(telefono);
    if (errorTelefono) {
        mostrarError(document.getElementById('telefono'), errorTelefono);
        hayErrores = true;
    } else {
        limpiarError(document.getElementById('telefono'));
    }
    
    // Validar motivo
    const errorMotivo = validarMotivo(motivo);
    if (errorMotivo) {
        mostrarError(document.getElementById('motivo'), errorMotivo);
        hayErrores = true;
    } else {
        limpiarError(document.getElementById('motivo'));
    }
    
    return !hayErrores;
}

// Modificar el event listener del formulario
document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointmentForm');
    const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
    const responseModalLabel = document.getElementById('responseModalLabel');
    const responseModalMessage = document.getElementById('responseModalMessage');
    const responseModalHeader = document.getElementById('responseModalHeader');

    // Agregar validación en tiempo real
    const inputs = appointmentForm.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const valor = this.value.trim();
            let error = '';
            
            switch(this.id) {
                case 'nombre':
                    error = validarNombre(valor);
                    break;
                case 'apellido':
                    error = validarApellido(valor);
                    break;
                case 'telefono':
                    error = validarTelefono(valor);
                    break;
                case 'motivo':
                    error = validarMotivo(valor);
                    break;
            }
            
            if (error) {
                mostrarError(this, error);
            } else {
                limpiarError(this);
            }
        });
        
        input.addEventListener('blur', function() {
            const valor = this.value.trim();
            let error = '';
            
            switch(this.id) {
                case 'nombre':
                    error = validarNombre(valor);
                    break;
                case 'apellido':
                    error = validarApellido(valor);
                    break;
                case 'telefono':
                    error = validarTelefono(valor);
                    break;
                case 'motivo':
                    error = validarMotivo(valor);
                    break;
            }
            
            if (error) {
                mostrarError(this, error);
            } else {
                limpiarError(this);
            }
        });
    });

    appointmentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validar el formulario antes de enviar
        if (!validarFormulario()) {
            // Mostrar mensaje de error general
            responseModalHeader.className = 'modal-header bg-danger text-white';
            responseModalLabel.textContent = 'Error de Validación';
            responseModalMessage.textContent = 'Por favor, corrija los errores en el formulario antes de enviar.';
            responseModal.show();
            return;
        }

        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(this);
        formData.append('accion', 'incluir');

        // Mostrar el loader
        document.getElementById('loader').style.display = 'flex';

        // Enviar los datos al servidor
        fetch('?pagina=publicidad', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Ocultar el loader
            document.getElementById('loader').style.display = 'none';

            // Configurar el modal de respuesta
            if (data.resultado === 'ok') {
                responseModalHeader.className = 'modal-header bg-success text-white';
                responseModalLabel.textContent = '¡Éxito!';
                responseModalMessage.textContent = data.mensaje;
                appointmentForm.reset();
                // Limpiar todos los errores visuales
                inputs.forEach(input => limpiarError(input));
            } else {
                responseModalHeader.className = 'modal-header bg-danger text-white';
                responseModalLabel.textContent = 'Error';
                responseModalMessage.textContent = data.mensaje;
            }

            // Mostrar el modal
            responseModal.show();
        })
        .catch(error => {
            // Ocultar el loader
            document.getElementById('loader').style.display = 'none';

            // Mostrar error en el modal
            responseModalHeader.className = 'modal-header bg-danger text-white';
            responseModalLabel.textContent = 'Error';
            responseModalMessage.textContent = 'Hubo un error al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.';
            responseModal.show();

            console.error('Error:', error);
        });
    });
}); 