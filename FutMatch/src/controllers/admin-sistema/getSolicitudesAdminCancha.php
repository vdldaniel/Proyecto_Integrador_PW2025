<?php
require_once '../../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

header('Content-Type: application/json');

// Obtener el estado filtrado (por defecto: pendientes = 1)
$id_estado = isset($_GET['estado']) ? intval($_GET['estado']) : 1;

try {
    $query = 'SELECT 
        s.id_solicitud,
        s.nombre,
        s.apellido,
        s.email,
        s.telefono,
        s.nombre_cancha,
        s.observaciones,
        s.fecha_solicitud,
        s.id_estado,
        s.id_verificador,
        d.direccion_completa,
        d.latitud,
        d.longitud,
        d.pais,
        d.provincia,
        d.localidad,
        es.nombre as estado_nombre,
        CONCAT(u.nombre, " ", u.apellido) as verificador_nombre
    FROM solicitudes_admin_cancha s
    LEFT JOIN direcciones d ON s.id_direccion = d.id_direccion
    LEFT JOIN estados_solicitudes es ON s.id_estado = es.id_estado
    LEFT JOIN usuarios u ON s.id_verificador = u.id_usuario
    WHERE s.id_estado = :id_estado
    ORDER BY s.fecha_solicitud DESC';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($solicitudes);
} catch (PDOException $e) {
    error_log("Error en getSolicitudesAdminCancha: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener solicitudes']);
}
