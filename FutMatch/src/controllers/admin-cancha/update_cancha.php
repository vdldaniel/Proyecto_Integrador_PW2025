<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

try {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Método no permitido.");
    }

    // ========================
    // CAPTURAR PARÁMETROS
    // ========================
    $id_cancha = $_POST["id_cancha"] ?? null;
    $nombre = $_POST["nombre"] ?? null;
    $superficie = $_POST["superficie"] ?? null;
    $ubicacion = $_POST["ubicacion"] ?? null;
    $descripcion = $_POST["descripcion"] ?? null;
    $tipoCancha = $_POST["tipo_cancha"] ?? null;

    if (!$id_cancha) {
        throw new Exception("ID de cancha no recibido.");
    }

 
    $stmt = $conn->prepare("
        SELECT id_direccion 
        FROM canchas 
        WHERE id_cancha = ?
    ");
    $stmt->execute([$id_cancha]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("La cancha no existe.");
    }

    $id_direccion = $row["id_direccion"];

  
    // ACTUALIZAR SOLO direccion_completa
    // ================================
    $stmt = $conn->prepare("
        UPDATE direcciones 
        SET direccion_completa = ?
        WHERE id_direccion = ?
    ");
    $stmt->execute([$ubicacion, $id_direccion]);


    $stmt = $conn->prepare("
        UPDATE canchas 
        SET 
            nombre = ?, 
            id_superficie = ?, 
            descripcion = ?,
            tipo_cancha = ?
        WHERE id_cancha = ?
    ");
    $stmt->execute([
        $nombre,
        $superficie,
        $descripcion,
        $tipoCancha,
        $id_cancha
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Cancha actualizada correctamente."
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

