<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'perfilEquipo';
$page_title = "Perfil de Equipo - FutMatch Admin";
// No necesitamos CSS específico adicional para esta página

// Configuración del componente de perfil equipo - Vista Admin Cancha
$perfil_equipo_admin_mode = true;
$perfil_equipo_editar_mode = false; // Admin no edita, solo visualiza
$perfil_equipo_titulo_header = 'Los Tigres FC';
$perfil_equipo_subtitulo_header = 'Equipo fundado el 15 de Octubre, 2024 • Estado: Activo';
$perfil_equipo_botones_header = [
    [
        'tipo' => 'button',
        'texto' => 'Información completa',
        'clase' => 'btn-dark',
        'icono' => 'bi bi-info-circle',
        'modal' => '#modalInfoEquipo'
    ],
    [
        'tipo' => 'button',
        'texto' => 'Gestionar equipo',
        'clase' => 'btn-dark',
        'icono' => 'bi bi-gear',
        'id' => 'btnGestionarEquipo'
    ],
    [
        'tipo' => 'link',
        'texto' => 'Ver torneos',
        'clase' => 'btn-primary',
        'icono' => 'bi bi-trophy',
        'url' => 'misTorneos.php'
    ]
];
$perfil_equipo_mostrar_pestanas = ['info', 'jugadores', 'estadisticas', 'partidos'];
$perfil_equipo_datos_equipo = [
    'nombre' => 'Los Tigres FC',
    'lider' => 'Carlos Rodríguez (@carlos_lider)',
    'fecha_creacion' => '15 de Octubre, 2024',
    'integrantes' => 8,
    'torneos_activos' => 2,
    'partidos_jugados' => 12,
    'codigo_equipo' => 'TIG2024',
    'estado' => 'Activo'
];
$perfil_equipo_jugadores = [
    [
        'id' => 1,
        'nombre' => 'Carlos Rodríguez',
        'username' => '@carlos_lider',
        'posicion' => 'Delantero',
        'es_lider' => true,
        'calificacion' => 4.8,
        'partidos' => 45,
        'goles' => 23,
        'estado' => 'Activo'
    ],
    [
        'id' => 2,
        'nombre' => 'Miguel Torres',
        'username' => '@miguel_def',
        'posicion' => 'Defensor',
        'es_lider' => false,
        'calificacion' => 4.5,
        'partidos' => 38,
        'goles' => 3,
        'estado' => 'Activo'
    ],
    [
        'id' => 3,
        'nombre' => 'Juan Pérez',
        'username' => '@juanpe_mid',
        'posicion' => 'Mediocampo',
        'es_lider' => false,
        'calificacion' => 4.3,
        'partidos' => 42,
        'goles' => 8,
        'estado' => 'Activo'
    ],
    [
        'id' => 4,
        'nombre' => 'Luis González',
        'username' => '@luis_goal',
        'posicion' => 'Portero',
        'es_lider' => false,
        'calificacion' => 4.6,
        'partidos' => 35,
        'goles' => 0,
        'estado' => 'Activo'
    ]
];
$perfil_equipo_estadisticas = [
    'partidos_jugados' => 12,
    'partidos_ganados' => 8,
    'partidos_empatados' => 2,
    'partidos_perdidos' => 2,
    'goles_favor' => 34,
    'goles_contra' => 12,
    'diferencia_goles' => 22,
    'puntos' => 26,
    'racha_actual' => 'G-G-E-G-G'
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
    <?php
    // Cargar navbar de admin cancha
    require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <?php
        // Incluir componente de perfil de equipo
        include __DIR__ . '/../perfilEquipo.php';
        ?>
    </main>

    <!-- Modal adicional para gestión de equipo (específico de admin) -->
    <div class="modal fade" id="modalGestionEquipo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-gear"></i> Gestión del Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-dark">
                            <i class="bi bi-ban"></i> Suspender Equipo
                        </button>
                        <button class="btn btn-dark">
                            <i class="bi bi-exclamation-triangle"></i> Enviar Advertencia
                        </button>
                        <button class="btn btn-dark">
                            <i class="bi bi-envelope"></i> Contactar Líder
                        </button>
                        <hr>
                        <button class="btn btn-dark">
                            <i class="bi bi-trash"></i> Eliminar Equipo
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script específico de admin cancha
        document.getElementById('btnGestionarEquipo').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalGestionEquipo'));
            modal.show();
        });
    </script>
</body>

</html>