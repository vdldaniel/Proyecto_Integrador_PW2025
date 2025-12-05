<?php


ob_start();


ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// ** FIN: CONTROL DE SALIDA Y ERRORES **

require_once __DIR__ . '/../../app/config.php';


ob_clean();

header("Content-Type: application/json");

if ($conn === null) {
    http_response_code(503); // Service Unavailable
    // Limpiamos el buffer por si acaso y enviamos el JSON de error.
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Error de conexión con la base de datos."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    // Antes de enviar CUALQUIER JSON, nos aseguramos de que el buffer esté limpio y cerrado.
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Método no permitido. Solo se acepta POST."]);
    exit;
}

// ID de la etapa 'cancelado'.
const ETAPA_CANCELADO_ID = 5;


$torneoId = filter_input(INPUT_POST, 'torneo_id', FILTER_VALIDATE_INT);
$idAdminCancha = $_SESSION['user_id'] ?? 1; // Usamos el ID de sesión o el de prueba 1

if (!$torneoId) {
    http_response_code(400);
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "ID de torneo no válido."]);
    exit;
}

if (!$idAdminCancha) {
    http_response_code(401);
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit;
}

try {
    // 1. Verificar que el torneo pertenezca a este organizador
    $sqlVerificar = "SELECT id_torneo FROM torneos WHERE id_torneo = :torneo_id AND id_organizador = :id_organizador";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    // USAMOS bindValue() para evitar el error de referencia de PDO.
    $stmtVerificar->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtVerificar->bindValue(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificar->execute();

    if ($stmtVerificar->rowCount() === 0) {
        http_response_code(403);
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Permiso denegado o torneo no encontrado."]);
        exit;
    }

    // 2. Actualizar la etapa del torneo a "cancelado"
    $sql = "UPDATE torneos SET id_etapa = :etapa_cancelado_id WHERE id_torneo = :torneo_id";
    $stmt = $conn->prepare($sql);
    // USAMOS bindValue() para evitar el error de referencia de PDO.
    $stmt->bindValue(':etapa_cancelado_id', ETAPA_CANCELADO_ID, PDO::PARAM_INT);
    $stmt->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmt->execute();

    // 3. Respuesta de éxito
    // CERRAMOS el buffer y enviamos el JSON.
    ob_end_clean();
    echo json_encode(["status" => "success", "message" => "Torneo cancelado exitosamente."]);
} catch (Exception $e) {
    // En caso de cualquier error dentro del TRY, limpiamos y cerramos el buffer.
    ob_end_clean();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al cancelar el torneo: " . $e->getMessage()]);
}
