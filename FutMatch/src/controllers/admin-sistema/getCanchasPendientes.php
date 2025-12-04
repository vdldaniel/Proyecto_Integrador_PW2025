<?php
require_once(__DIR__ . "/../../app/config.php");

header('Content-Type: application/json');

try {
    /* Verificar que el usuario esté autenticado como admin sistema
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin_sistema') {
        http_response_code(401);
        echo json_encode([
            'error' => 'No autorizado',
            'debug' => [
                'session_started' => session_status() === PHP_SESSION_ACTIVE,
                'user_id_set' => isset($_SESSION['user_id']),
                'user_type' => $_SESSION['user_type'] ?? 'no definido',
                'expected' => 'admin_sistema'
            ]
        ]);
        exit;
    }*/

    // Obtener parámetro de estado (opcional)
    $id_estado = isset($_GET['id_estado']) ? intval($_GET['id_estado']) : null;

    // Construir consulta SQL - usar SELECT completo en lugar de vista
    $sql = "SELECT 
                c.id_cancha,
                c.nombre AS nombre_cancha,
                c.telefono AS telefono_cancha,
                c.id_estado,
                e.nombre AS estado_cancha,
                c.id_admin_cancha,
                u.nombre AS nombre_admin,
                u.apellido AS apellido_admin,
                u.email AS email_admin,
                ac.telefono AS telefono_admin,
                d.direccion_completa,
                d.pais,
                d.provincia,
                d.localidad,
                d.latitud,
                d.longitud,
                c.fecha_creacion,
                c.id_verificador,
                v.nombre AS nombre_verificador,
                v.apellido AS apellido_verificador
            FROM canchas c
            INNER JOIN estados_canchas e ON c.id_estado = e.id_estado
            INNER JOIN usuarios u ON c.id_admin_cancha = u.id_usuario
            INNER JOIN admin_canchas ac ON c.id_admin_cancha = ac.id_admin_cancha
            INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
            LEFT JOIN usuarios v ON c.id_verificador = v.id_usuario
            WHERE 1=1";

    // Filtrar por estado si se proporciona
    if ($id_estado !== null) {
        $sql .= " AND c.id_estado = :id_estado";
    }

    $sql .= " ORDER BY c.fecha_creacion DESC";

    $stmt = $conn->prepare($sql);

    if ($id_estado !== null) {
        $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
    }

    $stmt->execute();
    $canchas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatear respuesta
    $response = [
        'success' => true,
        'data' => $canchas,
        'total' => count($canchas)
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener las canchas',
        'message' => $e->getMessage()
    ]);
}
