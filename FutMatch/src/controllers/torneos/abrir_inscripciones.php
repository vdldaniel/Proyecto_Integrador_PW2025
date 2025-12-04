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

try {
    // Leer JSON enviado desde JS
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    // Validaciones básicas
    if (!isset($data["torneo_id"], $data["fecha_cierre"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Datos incompletos."
        ]);
        exit;
    }

    $torneoId = intval($data["torneo_id"]);
    $fechaCierre = trim($data["fecha_cierre"]);

    if ($torneoId <= 0) {
        echo json_encode(["status" => "error", "message" => "Torneo inválido."]);
        exit;
    }

    if (empty($fechaCierre)) {
        echo json_encode(["status" => "error", "message" => "Debe seleccionar una fecha de cierre."]);
        exit;
    }

    // Verificar que no sea fecha pasada
    if (strtotime($fechaCierre) < strtotime(date("Y-m-d"))) {
        echo json_encode([
            "status" => "error",
            "message" => "La fecha de cierre no puede ser anterior a hoy."
        ]);
        exit;
    }

    
    //  Obtener fecha de inicio del torneo
    // ---------------------------------------------------
    $sqlTorneo = "SELECT fecha_inicio FROM torneos WHERE id_torneo = :id";
    $stmtT = $conn->prepare($sqlTorneo);
    $stmtT->bindParam(":id", $torneoId, PDO::PARAM_INT);
    $stmtT->execute();
    $torneo = $stmtT->fetch(PDO::FETCH_ASSOC);

    if (!$torneo) {
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado."]);
        exit;
    }

    $fechaInicio = $torneo["fecha_inicio"];

    if (!$fechaInicio) {
        echo json_encode([
            "status" => "error",
            "message" => "El torneo no tiene fecha de inicio definida."
        ]);
        exit;
    }

   //Validar que la fecha límite sea al menos 5 días antes
   
    $fechaMaximaPermitida = date("Y-m-d", strtotime($fechaInicio . " -5 days"));

    if ($fechaCierre > $fechaMaximaPermitida) {
        echo json_encode([
            "status" => "error",
            "message" => "La fecha límite debe ser al menos 5 días antes del inicio del torneo (" . $fechaMaximaPermitida . ")."
        ]);
        exit;
    }

  
    $sql = "UPDATE torneos 
            SET id_etapa = 2,
                fin_estimativo = :fecha_cierre
            WHERE id_torneo = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $torneoId, PDO::PARAM_INT);
    $stmt->bindParam(":fecha_cierre", $fechaCierre);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Inscripciones abiertas correctamente."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No se pudo actualizar el torneo."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error interno: " . $e->getMessage()
    ]);
}
