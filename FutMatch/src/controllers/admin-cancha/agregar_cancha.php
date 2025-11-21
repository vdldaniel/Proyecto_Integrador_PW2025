<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Validación básica
if (
    empty($_POST["nombre"]) ||
    empty($_POST["superficie"]) ||
    empty($_POST["ubicacion"]) ||
    empty($_POST["descripcion"]) ||
    empty($_POST["tipo_cancha"])
) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

$nombre       = $_POST["nombre"];
$superficie   = $_POST["superficie"];   // id_superficie
$ubicacion    = $_POST["ubicacion"];
$descripcion  = $_POST["descripcion"];
$tipoCancha   = $_POST["tipo_cancha"];  // id_tipo

try {

    // 1) Insertar dirección
    $sqlDireccion = "INSERT INTO direcciones (direccion_completa, latitud, longitud) 
                     VALUES (?, 0, 0)";
    $stmt = $conn->prepare($sqlDireccion);
    $stmt->execute([$ubicacion]);
    $idDireccion = $conn->lastInsertId();

    // 2) Insertar cancha (corregido)
    $sqlCancha = "INSERT INTO canchas 
        (id_admin_cancha, id_direccion, nombre, descripcion, id_tipo, id_estado, id_superficie, politicas_reservas)
        VALUES (1, ?, ?, ?, ?, 1, ?, '')";

    $stmt2 = $conn->prepare($sqlCancha);
    $stmt2->execute([$idDireccion, $nombre, $descripcion, $tipoCancha, $superficie]);

    echo json_encode(["status" => "success"]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

