<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once AUTH_REQUIRED_COMPONENT;

// Resalta la página actual en el navbar
$current_page = 'perfilEquipo';
$page_title = "Perfil de Equipo - FutMatch";

$page_css = [CSS_PAGES_PERFILES];

// Configuración del componente de perfil equipo - Vista Jugador
$perfil_equipo_admin_mode = false;
$perfil_equipo_editar_mode = true; // Asumimos que es el líder del equipo
$perfil_equipo_titulo_header = 'Los Tigres FC';
$perfil_equipo_subtitulo_header = 'Tu equipo • Fundado el 15 de Octubre, 2024';
$perfil_equipo_mostrar_pestanas = ['info', 'jugadores', 'estadisticas'];
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
    ],
    [
        'id' => 5,
        'nombre' => 'Ana María Ruiz',
        'username' => '@ana_atacante',
        'posicion' => 'Delantero',
        'es_lider' => false,
        'calificacion' => 4.7,
        'partidos' => 28,
        'goles' => 15,
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
    // Cargar navbar de jugador
    require_once NAVBAR_JUGADOR_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <?php
        // Incluir componente de perfil de equipo
        include __DIR__ . '/../perfilEquipo.php';
        ?>
    </main>

    <!-- Modal adicional para configuración del equipo (específico de jugador líder) -->
    <div class="modal fade" id="modalConfigEquipo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-gear"></i> Configuración del Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formConfigEquipo">
                        <div class="mb-3">
                            <label class="form-label">Nombre del equipo</label>
                            <input type="text" class="form-control" value="<?= $perfil_equipo_datos_equipo['nombre'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código del equipo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= $perfil_equipo_datos_equipo['codigo_equipo'] ?>" readonly>
                                <button class="btn btn-dark" type="button" id="btnGenerarCodigo">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Los jugadores usan este código para unirse al equipo</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción del equipo</label>
                            <textarea class="form-control" rows="3" placeholder="Descripción opcional del equipo..."></textarea>
                        </div>
                    </form>

                    <hr>

                    <h6 class="text-danger">Zona de Peligro</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-dark">
                            <i class="bi bi-people"></i> Transferir Liderazgo
                        </button>
                        <button class="btn btn-dark">
                            <i class="bi bi-trash"></i> Disolver Equipo
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script específico de jugador
        document.getElementById('btnConfigEquipo').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalConfigEquipo'));
            modal.show();
        });

        // Generar nuevo código de equipo
        document.getElementById('btnGenerarCodigo').addEventListener('click', function() {
            const input = this.previousElementSibling;
            const nuevoCodigo = 'TIG' + Math.random().toString(36).substr(2, 4).toUpperCase();
            input.value = nuevoCodigo;

            // Mostrar confirmación
            if (confirm('¿Estás seguro de que quieres generar un nuevo código? El código anterior dejará de funcionar.')) {
                // Aquí iría la lógica para actualizar en el servidor
                console.log('Nuevo código generado:', nuevoCodigo);
            } else {
                // Restaurar código anterior
                input.value = '<?= $perfil_equipo_datos_equipo['codigo_equipo'] ?>';
            }
        });
    </script>
</body>

</html>