<?php

require_once __DIR__ . '/../../app/config.php'; 
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

// 1. Recolección de datos (SOLO los campos presentes en el HTML)
$nombre                     = $_POST['nombre'] ?? null;
$fechaInicio                = $_POST['fechaInicio'] ?? null;
$fechaFin                   = $_POST['fechaFin'] ?? null;

$descripcion                = $_POST['descripcion'] ?? null;
$abrirInscripciones         = filter_var($_POST['abrirInscripciones'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
$fechaCierreInscripciones   = $_POST['fechaCierreInscripciones'] ?? null;

$idAdminCancha = $_SESSION['user_id']  ?? null;// Obtener ID del organizador logueado

// 2. Validación
if (!$nombre || !$fechaInicio || !$idAdminCancha) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "El nombre, fecha de inicio y organizador son obligatorios."]);
    exit;
}

if (!empty($fechaFin) && strtotime($fechaInicio) > strtotime($fechaFin)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "La fecha de fin no puede ser anterior a la de inicio."]);
    exit;
}

if ($abrirInscripciones && empty($fechaCierreInscripciones)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Debe ingresar una fecha de cierre de inscripciones."]);
    exit;
}

$id_etapa = $abrirInscripciones ? 2 : 1; // 1=Borrador, 2=Inscripciones Abiertas


$fecha_cierre_db = $abrirInscripciones ? $fechaCierreInscripciones : null;


try {
    $conn->beginTransaction();

    $sql = "INSERT INTO torneos (
                id_organizador, nombre, fecha_inicio, fecha_fin, fin_estimativo, id_etapa, descripcion
            ) VALUES (
                :id_organizador, :nombre, :fecha_inicio, :fecha_fin, :fin_estimativo, :id_etapa, :descripcion
            )";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_fin', $fechaFin, PDO::PARAM_STR);
    $stmt->bindParam(':fin_estimativo', $fecha_cierre_db, PDO::PARAM_STR); // Fecha de cierre
    $stmt->bindParam(':id_etapa', $id_etapa, PDO::PARAM_INT);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    
    $stmt->execute();
    $id_torneo = $conn->lastInsertId();
    
    $conn->commit();

    echo json_encode(["status" => "success", "id_torneo" => $id_torneo, "message" => "Torneo creado con éxito."]);

} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al guardar: " . $e->getMessage()]);
}