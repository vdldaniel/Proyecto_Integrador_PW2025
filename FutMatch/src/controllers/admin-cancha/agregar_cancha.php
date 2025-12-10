<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

$idAdminCancha = $_SESSION['user_id'] ?? 1;

if (!$idAdminCancha) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Usuario no autenticado."
    ]);
    exit;
}

$nombre          = $_POST['nombre'] ?? null;
$superficie      = $_POST['superficie'] ?? null;
$ubicacion       = $_POST['ubicacion'] ?? null;
$descripcion     = $_POST['descripcion'] ?? null;
$id_tipo_partido = $_POST['id_tipo_partido'] ?? null;
$latitud      = $_POST['latitud'] ?? 0;
$longitud     = $_POST['longitud'] ?? 0;

if (!$nombre || !$superficie || !$ubicacion || !$id_tipo_partido) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

try {
    $conn->beginTransaction();

    // Insertar dirección
    $sql = "INSERT INTO direcciones (direccion_completa, latitud, longitud)
            VALUES (?, 0, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ubicacion, $latitud, $longitud]);
    $id_direccion = $conn->lastInsertId();

    // Insertar cancha
    $sql = "INSERT INTO canchas 
            (id_admin_cancha, id_direccion, nombre, descripcion, id_estado, id_superficie, politicas_reservas)
            VALUES (:id_admin_cancha, :id_direccion, :nombre, :descripcion, 1, :superficie, NULL)";

    $sql = "INSERT INTO canchas (id_admin_cancha, id_direccion, nombre, descripcion, id_estado, id_superficie, politicas_reservas)
            VALUES (:id_admin_cancha, :id_direccion, :nombre, :descripcion, 1, :id_superficie, NULL)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_admin_cancha', $idAdminCancha, PDO::PARAM_INT);
    $stmt->bindParam(':id_direccion', $id_direccion, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':superficie', $superficie, PDO::PARAM_INT);
    $stmt->execute();

    $id_cancha = $conn->lastInsertId();

    // Insertar tipo de partido
    $sql = "INSERT INTO canchas_tipos_partido (id_cancha, id_tipo_partido, activo)
            VALUES (?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_cancha, $id_tipo_partido]);

    $conn->commit();

    echo json_encode(["status" => "success", "id_cancha" => $id_cancha]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

