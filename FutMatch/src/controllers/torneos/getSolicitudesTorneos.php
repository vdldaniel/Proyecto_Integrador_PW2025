<?php
require_once '../../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación y rol
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin_cancha') {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

try {
    $id_torneo = isset($_GET['id_torneo']) ? intval($_GET['id_torneo']) : 0;

    if ($id_torneo === 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID de torneo inválido']);
        exit();
    }

    // Verificar que el torneo pertenece al admin_cancha actual
    $stmt = $conn->prepare("SELECT id_organizador FROM torneos WHERE id_torneo = :id_torneo");
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmt->execute();
    $torneo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$torneo || $torneo['id_organizador'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No tienes permisos para ver este torneo']);
        exit();
    }

    // Obtener equipos participantes (aprobados - id_estado = 3)
    $sql_participantes = "
        SELECT 
            e.id_equipo,
            e.nombre AS nombre_equipo,
            e.foto,
            CONCAT(u.nombre, ' ', u.apellido) AS lider_nombre,
            (SELECT COUNT(*) FROM jugadores_equipos je WHERE je.id_equipo = e.id_equipo AND je.estado_solicitud = 3) AS total_integrantes
        FROM equipos_torneos et
        JOIN equipos e ON et.id_equipo = e.id_equipo
        JOIN usuarios u ON e.id_lider = u.id_usuario
        WHERE et.id_torneo = :id_torneo AND et.id_estado = 3
        ORDER BY e.nombre ASC
    ";

    $stmt = $conn->prepare($sql_participantes);
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmt->execute();
    $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener solicitudes pendientes (id_estado = 1)
    $sql_pendientes = "
        SELECT 
            e.id_equipo,
            e.nombre AS nombre_equipo,
            e.foto,
            CONCAT(u.nombre, ' ', u.apellido) AS lider_nombre,
            (SELECT COUNT(*) FROM jugadores_equipos je WHERE je.id_equipo = e.id_equipo AND je.estado_solicitud = 3) AS total_integrantes
        FROM equipos_torneos et
        JOIN equipos e ON et.id_equipo = e.id_equipo
        JOIN usuarios u ON e.id_lider = u.id_usuario
        WHERE et.id_torneo = :id_torneo AND et.id_estado = 1
        ORDER BY e.nombre ASC
    ";

    $stmt = $conn->prepare($sql_pendientes);
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmt->execute();
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => [
            'participantes' => $participantes,
            'pendientes' => $pendientes
        ]
    ]);
} catch (PDOException $e) {
    error_log("GET_SOLICITUDES_TORNEOS ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener las solicitudes'
    ]);
}
