<?php
require "../conexion.php";

$id_cancha = $_GET['id_cancha'];

// Obtener datos de la cancha
$stmt = $conn->prepare("
    SELECT c.*, d.direccion_completa, a.telefono 
    FROM canchas c
    INNER JOIN direcciones d ON d.id_direccion = c.id_direccion
    INNER JOIN admin_canchas a ON a.id_admin_cancha = c.id_admin_cancha
    WHERE c.id_cancha = ?
");
$stmt->execute([$id_cancha]);
$cancha = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener días de semana
$dias = $conn->query("
    SELECT * FROM dias_semana ORDER BY id_dia
")->fetchAll(PDO::FETCH_ASSOC);

// Obtener horarios de la cancha
$stmt = $conn->prepare("
    SELECT * FROM horarios_cancha WHERE id_cancha = ?
");
$stmt->execute([$id_cancha]);
$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "cancha" => $cancha,
    "dias" => $dias,
    "horarios" => $horarios
]);
