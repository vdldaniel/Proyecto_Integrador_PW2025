<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status"=>"error","message"=>"Método no permitido"]);
    exit;
}

$nombre       = $_POST['nombre'] ?? null;
$superficie   = $_POST['superficie'] ?? null;
$ubicacion    = $_POST['ubicacion'] ?? null;
$descripcion  = $_POST['descripcion'] ?? null;
$id_tipo_partido = $_POST['id_tipo_partido'] ?? null; 

if (!$nombre || !$superficie || !$ubicacion || !$id_tipo_partido) {
    echo json_encode(["status"=>"error","message"=>"Datos incompletos"]);
    exit;
}

try {
    $conn->beginTransaction();

   
    $sql = "INSERT INTO direcciones (direccion_completa, latitud, longitud) VALUES (?, 0, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ubicacion]);
    $id_direccion = $conn->lastInsertId();

 
    $sql = "INSERT INTO canchas (id_admin_cancha, id_direccion, nombre, descripcion, id_estado, id_superficie, politicas_reservas)
            VALUES (1, ?, ?, ?, 1, ?, NULL)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_direccion, $nombre, $descripcion, $superficie]);
    $id_cancha = $conn->lastInsertId();

 
    $sql = "INSERT INTO canchas_tipos_partido (id_cancha, id_tipo_partido, activo) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_cancha, $id_tipo_partido]);

    $conn->commit();

    echo json_encode(["status"=>"success","id_cancha"=>$id_cancha]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}
