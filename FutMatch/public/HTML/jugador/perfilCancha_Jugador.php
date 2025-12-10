<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchaPerfilJugador';
$page_title = "Perfil de Cancha - FutMatch";
$page_css = [CSS_PAGES_PERFILES];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
    <?php
    // Cargar navbar de jugador si está logueado
    if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'jugador') {
        $navbar_jugador_active = true;
        require_once NAVBAR_JUGADOR_COMPONENT;
    } else {
        $navbar_jugador_active = false;
        require_once NAVBAR_GUEST_COMPONENT;
    }

    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <?php
        // Variables de configuración para el componente perfil de cancha (modo jugador)
        $perfil_cancha_admin_mode = false;
        $perfil_cancha_mostrar_selector = false;
        $perfil_cancha_titulo_seccion = 'Torneos Disponibles';
        $perfil_cancha_descripcion = 'Información detallada y disponibilidad de la cancha';
        $perfil_cancha_boton_primario = [
            'texto' => 'Ver disponibilidad',
            'icono' => 'bi-calendar-plus',
            'url' => PAGE_CALENDARIO_CANCHA_JUGADOR
        ];

        // Valores por defecto (serán reemplazados por JavaScript desde el backend)
        $perfil_cancha_nombre = 'Cargando...';
        $perfil_cancha_descripcion_banner = 'Cargando información de la cancha...';
        $perfil_cancha_banner = 'Cargando...';
        $perfil_cancha_direccion = 'Cargando...';
        $perfil_cancha_tipo = 'N/A';
        $perfil_cancha_superficie = 'N/A';
        $perfil_cancha_capacidad = '0';
        $perfil_cancha_total_jugadores = '0';
        $perfil_cancha_total_partidos = '0';
        $perfil_cancha_dias_atencion = 'Lunes a Domingo';
        $perfil_cancha_horario = '08:00 - 22:00';
        $perfil_cancha_estado_actual = 'Cargando...';
        $perfil_cancha_hora_cierre = '22:00';

        // Incluir componente de perfil de cancha
        include PERFIL_CANCHA_COMPONENT;
        ?>
    </main>



    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>

    <!-- Constantes JavaScript -->
    <script>
        const GET_INFO_PERFIL = '<?= GET_INFO_PERFIL ?>';
        const GET_HORARIOS_CANCHAS = '<?= GET_HORARIOS_CANCHAS ?>';
        const BASE_URL = '<?= BASE_URL ?>';
        const PAGE_CALENDARIO_CANCHA_JUGADOR = '<?= PAGE_CALENDARIO_CANCHA_JUGADOR ?>';

        // Obtener id_cancha del query string
        const urlParams = new URLSearchParams(window.location.search);
        const ID_CANCHA = urlParams.get('id') || urlParams.get('id_cancha');

        if (!ID_CANCHA) {
            console.warn('No se proporcionó un ID de cancha en la URL');
        }
    </script>

    <!-- Script de perfiles compartido (debe ir primero) -->
    <script src="<?= JS_PERFILES ?>"></script>
    <!-- Script base de perfil de cancha (clase PerfilCanchaBase con métodos de horarios) -->
    <script src="<?= JS_PERFIL_CANCHA_BASE ?>"></script>
    <!-- Script específico del jugador (extiende el base) -->
    <script src="<?= BASE_URL ?>src/scripts/pages/cancha-perfil-jugador.js"></script>

    <!-- Cargar datos de la cancha al cargar la página -->
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            if (ID_CANCHA && window.perfilCanchaJugador) {
                try {
                    await window.perfilCanchaJugador.cargarYRenderizarCancha(ID_CANCHA);
                } catch (error) {
                    console.error('Error al cargar la cancha:', error);
                }
            }
        });
    </script>
</body>

</html>