<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../app/config.php';

if (!isset($_FILES["banner"]) || !isset($_POST["id_cancha"])) {
    echo json_encode(["status" => "error", "message" => "Datos faltantes"]);
    exit;
}

$id_cancha = intval($_POST["id_cancha"]);
$file = $_FILES["banner"];

$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

if (!in_array($ext, ["jpg", "jpeg", "png"])) {
    echo json_encode(["status" => "error", "message" => "Formato no permitido"]);
    exit;
}

$nombre_final = "banner_" . $id_cancha . "_" . time() . "." . $ext;
$directorioDestino = __DIR__ . '/../../public/ing/banners/';
    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

$ruta_destino = $directorioDestino . $nombre_final;

// Crear carpeta si no existe
if (!is_dir($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}

if (!move_uploaded_file($file["tmp_name"], $ruta_destino)) {
    echo json_encode(["status" => "error", "message" => "Error guardando archivo"]);
    exit;
}

// Actualizar en base de datos
$update = $conn->prepare("UPDATE canchas SET banner = ? WHERE id_cancha = ?");
$update->execute([$nombre_final, $id_cancha]);

echo json_encode([
    "status" => "success",
    "url" => $directorioDestino . $nombre_final  // URL correcta
]);
