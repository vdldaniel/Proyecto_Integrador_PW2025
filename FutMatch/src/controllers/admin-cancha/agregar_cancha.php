<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Validación básica
if (
    !isset($_POST["nombre"]) ||
    !isset($_POST["superficie"]) ||
    !isset($_POST["ubicacion"]) ||
    !isset($_POST["descripcion"]) ||
    !isset($_POST["tipo_cancha"])
) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

$nombre = $_POST["nombre"];
$superficie = $_POST["superficie"];
$ubicacion = $_POST["ubicacion"];
$descripcion = $_POST["descripcion"];
$tipo_cancha = $_POST["tipo_cancha"];

try {
    // 1) Insertar dirección primero
    $sqlDireccion = "INSERT INTO direcciones (direccion_completa, latitud, longitud) 
                     VALUES (?, 0, 0)";
    $stmt = $conn->prepare($sqlDireccion);
    $stmt->execute([$ubicacion]);
    $idDireccion = $conn->lastInsertId();

    // 2) Insertar cancha
    $sqlCancha = "INSERT INTO canchas 
        (id_admin_cancha, id_direccion, nombre, descripcion, id_estado, id_superficie, politicas_reservas,tipo_cancha)
        VALUES (1, ?, ?, ?, 1, ?, '')";

    $stmt2 = $conn->prepare($sqlCancha);
    $stmt2->execute([$idDireccion, $nombre, $descripcion, $superficie]);

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
