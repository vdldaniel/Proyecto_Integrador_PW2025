<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'miPerfilJugador';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Mi Perfil - FutMatch";

$page_css = [CSS_PAGES_PERFILES];
$page_js = [JS_PERFIL_JUGADOR];

// Variables para el componente perfilJugador.php
$perfil_jugador_admin_mode = false;
$perfil_jugador_es_propio = true;
$perfil_jugador_titulo_header = 'Mi Perfil';
$perfil_jugador_subtitulo_header = 'Gestiona tu información personal y estadísticas de juego';
$perfil_jugador_titulo_partidos = 'Mis Partidos Recientes';
$perfil_jugador_titulo_estadisticas = 'Mis Estadísticas';
$perfil_jugador_mostrar_reportar = false;
$perfil_jugador_mostrar_equipos = true;

$perfil_jugador_botones_header = [
    [
        'tipo' => 'link',
        'texto' => 'Ver Partidos',
        'icono' => 'bi-calendar-check',
        'clase' => 'btn-dark',
        'url' => PAGE_MIS_PARTIDOS_JUGADOR
    ],
    [
        'tipo' => 'button',
        'texto' => 'Configuración',
        'icono' => 'bi-gear',
        'clase' => 'btn-dark',
        'modal' => '#modalConfiguracion'
    ],
    [
        'tipo' => 'button',
        'texto' => 'Editar Perfil',
        'icono' => 'bi-pencil-square',
        'clase' => 'btn-primary',
        'id' => 'btnEditarPerfil'
    ]
];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_JUGADOR_COMPONENT; ?>

    <main>
        <div class="container mt-4">
            <?php include PERFIL_JUGADOR_COMPONENT; ?>
        </div>

        <!-- Modal Configuración -->
        <div class="modal fade" id="modalConfiguracion" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-gear"></i> Configuración de Perfil
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formConfiguracion">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control" name="nombre" value="Carlos Fernández" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" value="carlos_futbol" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="carlos.fernandez@email.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" name="telefono" value="+54 11 1234-5678">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de nacimiento</label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" value="1997-03-15">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" name="ubicacion" value="CABA, Buenos Aires">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Posición</label>
                                    <select class="form-select" name="posicion">
                                        <option value="arquero">Arquero</option>
                                        <option value="defensor">Defensor</option>
                                        <option value="mediocampista" selected>Mediocampista</option>
                                        <option value="delantero">Delantero</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pie hábil</label>
                                    <select class="form-select" name="pie">
                                        <option value="derecho" selected>Derecho</option>
                                        <option value="izquierdo">Izquierdo</option>
                                        <option value="ambos">Ambos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Especialidades (opcional)</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="pases_largos" checked>
                                            <label class="form-check-label">Pases largos</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="centros" checked>
                                            <label class="form-check-label">Centros</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="vision_juego" checked>
                                            <label class="form-check-label">Visión de juego</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="remates">
                                            <label class="form-check-label">Remates</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="defensa">
                                            <label class="form-check-label">Defensa</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especialidades[]" value="velocidad">
                                            <label class="form-check-label">Velocidad</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnGuardarConfiguracion">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <!-- Los principales están en perfilJugador.php -->
    <script>
        const CURRENT_USER_ID = '<?= $_SESSION['user_id'] ?>';
        const TIPO_PERFIL = 'jugador';
        const GET_INFO_PERFIL = '<?= GET_INFO_PERFIL ?>';
        const GET_PARTIDOS_JUGADOR = '<?= GET_PARTIDOS_JUGADOR ?>';
    </script>

    <script src="<?= JS_PERFIL_JUGADOR_BASE ?>"></script>

</body>

</html>