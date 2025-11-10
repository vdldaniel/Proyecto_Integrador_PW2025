<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'jugadoresReportadosAdmin';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Sistema - FutMatch";

$page_css = [];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>

    <main>
        <div class="container mt-4">

            <!-- Línea 1: Header con título y botones de navegación -->
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-1">Canchas</h1>
                    <p class="text-muted mb-0">Gestioná canchas y denuncias</p>
                </div>
            </div>

            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-outline-secondary me-2" id="btnHistorialCanchas" data-bs-toggle="modal" data-bs-target="#modalHistorialCanchas">
                    <i class="bi bi-clock-history"></i> Historial de Canchas
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCancha">
                    <i class="bi bi-plus-circle"></i> Agregar Cancha
                </button>
            </div>

        </div>
    </main>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
</body>

</html>