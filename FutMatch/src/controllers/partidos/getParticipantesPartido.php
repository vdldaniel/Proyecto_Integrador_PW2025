<?php
// Configurar manejo de errores
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Iniciar buffer de salida
ob_start();

try {
    require_once '../../app/config.php';
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Error al cargar configuración', 'detalle' => $e->getMessage()]);
    exit();
}

// Limpiar cualquier output
ob_clean();

header('Content-Type: application/json');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener el id_partido del parámetro GET
$id_partido = isset($_GET['id_partido']) ? intval($_GET['id_partido']) : 0;

if ($id_partido <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de partido requerido']);
    exit();
}

try {
    // Obtener participantes directamente de las tablas con LEFT JOIN para participantes externos
    $query = 'SELECT 
        pp.id_participante,
        pp.id_partido,
        pp.id_jugador,
        j.username AS username_jugador,
        u.nombre AS nombre_jugador,
        u.apellido AS apellido_jugador,
        pp.nombre_invitado,
        pp.id_rol,
        rp.nombre AS rol_participante,
        pp.id_estado,
        e.nombre AS estado_solicitud,
        pp.equipo,
        CASE pp.equipo 
            WHEN 1 THEN "Equipo A"
            WHEN 2 THEN "Equipo B"
            ELSE "Sin asignar"
        END AS equipo_asignado
    FROM participantes_partidos pp
    LEFT JOIN jugadores j ON pp.id_jugador = j.id_jugador
    LEFT JOIN usuarios u ON pp.id_jugador = u.id_usuario
    INNER JOIN roles_partidos rp ON pp.id_rol = rp.id_rol
    INNER JOIN estados_solicitudes e ON pp.id_estado = e.id_estado
    WHERE pp.id_partido = :id_partido 
    ORDER BY pp.id_estado DESC, pp.id_rol ASC';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
    $stmt->execute();

    $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $participantes
    ]);
} catch (PDOException $e) {
    error_log("GET_PARTICIPANTES_PARTIDO ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener participantes',
        'detalle' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("GET_PARTICIPANTES_PARTIDO ERROR GENERAL: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error inesperado',
        'detalle' => $e->getMessage()
    ]);
}
