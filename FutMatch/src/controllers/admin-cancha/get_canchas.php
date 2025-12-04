<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

$idAdminCancha = $_SESSION['user_id'] ?? 1;

if (!$idAdminCancha) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Usuario no autenticado."
    ]);
    exit;
}


if (empty($idAdminCancha)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username requerido']);
    exit();
}

try {

    $sql = "
        SELECT 
            c.id_cancha,
            c.id_admin_cancha,
            c.nombre,
            c.descripcion,
            c.telefono,
            c.id_superficie,
            c.id_estado,
            c.politicas_reservas,
            d.direccion_completa,

            -- Tipo de partido asociado
            tp.id_tipo_partido,
            tp.nombre AS tipo_nombre,
            tp.min_participantes,
            tp.max_participantes

        FROM canchas c

        LEFT JOIN direcciones d 
            ON c.id_direccion = d.id_direccion

        LEFT JOIN canchas_tipos_partido ctp 
            ON c.id_cancha = ctp.id_cancha
            AND ctp.activo = 1   -- solo tipo activo

        LEFT JOIN tipos_partido tp 
            ON ctp.id_tipo_partido = tp.id_tipo_partido

        WHERE id_admin_cancha = :id_admin_cancha

        ORDER BY c.id_cancha ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_admin_cancha', $id_admin_cancha, PDO::PARAM_STR);
    $stmt->execute();
    $canchas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data'   => $canchas
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
