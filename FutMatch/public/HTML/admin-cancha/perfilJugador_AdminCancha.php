<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'jugadorPerfilAdminCancha';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Perfil de Jugador - FutMatch Admin";

$page_css = [];
$page_js = [JS_PERFIL_JUGADOR];

// Variables para el componente perfilJugador.php
$perfil_jugador_admin_mode = false; // Admin de cancha no tiene permisos de admin de sistema
$perfil_jugador_es_propio = false;
$perfil_jugador_titulo_header = 'Perfil de Jugador';
$perfil_jugador_subtitulo_header = 'Información del jugador que utiliza sus servicios';
$perfil_jugador_titulo_partidos = 'Partidos en tu Cancha';
$perfil_jugador_titulo_estadisticas = 'Estadísticas del Jugador';
$perfil_jugador_mostrar_reportar = true;

$perfil_jugador_botones_header = [
    [
        'tipo' => 'link',
        'texto' => 'Volver',
        'icono' => 'bi-arrow-left',
        'clase' => 'btn-outline-secondary',
        'url' => PAGE_AGENDA_ADMIN_CANCHA // O la página de donde vino
    ],
    [
        'tipo' => 'button',
        'texto' => 'Reportar',
        'icono' => 'bi-flag',
        'clase' => 'btn-outline-danger',
        'modal' => '#modalReportarJugador'
    ]
];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_ADMIN_CANCHA_COMPONENT; ?>

    <main>
        <div class="container mt-4">
            <!-- Información adicional para admin de cancha -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Vista de Administrador de Cancha:</strong> Puedes ver el historial de este jugador en tu establecimiento y reportar comportamientos inapropiados.
                    </div>
                </div>
            </div>

            <?php include PERFIL_JUGADOR_COMPONENT; ?>

            <!-- Sección adicional: Historial en esta cancha -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0"><i class="bi bi-building"></i> Historial en tu Cancha</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-primary fs-4">23</div>
                                    <small class="text-muted">Reservas realizadas</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-success fs-4">21</div>
                                    <small class="text-muted">Reservas cumplidas</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-warning fs-4">2</div>
                                    <small class="text-muted">No shows</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-info fs-4">4.8★</div>
                                    <small class="text-muted">Calificación promedio</small>
                                </div>
                            </div>

                            <hr>

                            <h6>Últimas reservas:</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>08/11/2025 - 18:00</strong>
                                        <br>
                                        <small class="text-muted">Cancha A1-F5 • Partido amistoso</small>
                                    </div>
                                    <span class="badge bg-success">Cumplida</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>05/11/2025 - 20:00</strong>
                                        <br>
                                        <small class="text-muted">Cancha A2-F9 • Entrenamiento</small>
                                    </div>
                                    <span class="badge bg-success">Cumplida</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>02/11/2025 - 16:00</strong>
                                        <br>
                                        <small class="text-muted">Cancha A1-F5 • Partido amistoso</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">No show</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_PERFIL_JUGADOR ?>"></script>

</body>

</html>