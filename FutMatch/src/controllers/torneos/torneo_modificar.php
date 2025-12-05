<?php

require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

// 1. Recolección de datos
$id_torneo                  = $_POST['id_torneo'] ?? null;
$nombre                     = $_POST['nombre'] ?? null;
$fechaInicio                = $_POST['fechaInicio'] ?? null;
$fechaFin                   = $_POST['fechaFin'] ?? null;
$descripcion                = $_POST['descripcion'] ?? null;
$maxEquipos                 = isset($_POST['maxEquipos']) ? (int)$_POST['maxEquipos'] : null;

$idAdminCancha = $_SESSION['user_id'] ?? $_POST['idAdminCancha'] ?? null;

// 2. Validación
if (!$id_torneo || !$nombre || !$fechaInicio || !$idAdminCancha) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "El ID del torneo, nombre, fecha de inicio y organizador son obligatorios."]);
    exit;
}

if (!empty($fechaFin) && strtotime($fechaInicio) > strtotime($fechaFin)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "La fecha de fin no puede ser anterior a la de inicio."]);
    exit;
}

try {
    // Verificar que el torneo pertenece al organizador y está en borrador (id_etapa = 1)
    $sqlVerificar = "SELECT id_etapa FROM torneos WHERE id_torneo = :id_torneo AND id_organizador = :id_organizador";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmtVerificar->bindParam(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificar->execute();
    $torneo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$torneo) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado o sin permisos para modificarlo."]);
        exit;
    }

    if ($torneo['id_etapa'] != 1) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Solo se pueden modificar torneos en estado borrador."]);
        exit;
    }

    $conn->beginTransaction();

    $sql = "UPDATE torneos SET 
                nombre = :nombre, 
                fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, 
                descripcion = :descripcion,
                max_equipos = :max_equipos
            WHERE id_torneo = :id_torneo";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_fin', $fechaFin, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':max_equipos', $maxEquipos, PDO::PARAM_INT);
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);

    $stmt->execute();

    $conn->commit();

    echo json_encode(["status" => "success", "message" => "Torneo modificado con éxito."]);
} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al modificar: " . $e->getMessage()]);
}
