<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

$id_cancha = $_POST['id_cancha'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;
$ubicacion = $_POST['ubicacion'] ?? null;
$superficie = $_POST['superficie'] ?? null;
$id_tipo_partido = $_POST['id_tipo_partido'] ?? null;
$latitud = $_POST['latitud'] ?? 0;
$longitud = $_POST['longitud'] ?? 0;

if (!$id_cancha || !$nombre || !$superficie) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

try {
    $conn->beginTransaction();


    $stmt = $conn->prepare("SELECT id_direccion FROM canchas WHERE id_cancha = ?");
    $stmt->execute([$id_cancha]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) throw new Exception("Cancha no encontrada");

    $id_direccion = $row['id_direccion'];

    //actualizar direccion con coordenadas
    $stmt = $conn->prepare("UPDATE direcciones SET direccion_completa = ?, latitud = ?, longitud = ? WHERE id_direccion = ?");
    $stmt->execute([$ubicacion, $latitud, $longitud, $id_direccion]);

    //actualizar canchas
    $stmt = $conn->prepare("UPDATE canchas SET nombre = ?, descripcion = ?, id_superficie = ? WHERE id_cancha = ?");
    $stmt->execute([$nombre, $descripcion, $superficie, $id_cancha]);

    if ($id_tipo_partido) {

        $stmt = $conn->prepare("UPDATE canchas_tipos_partido SET activo = 0 WHERE id_cancha = ?");
        $stmt->execute([$id_cancha]);

        // Usar INSERT ... ON DUPLICATE KEY UPDATE para evitar error de clave duplicada
        $stmt = $conn->prepare("
            INSERT INTO canchas_tipos_partido (id_cancha, id_tipo_partido, activo) 
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE activo = 1
        ");
        $stmt->execute([$id_cancha, $id_tipo_partido]);
    }

    $conn->commit();
    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
