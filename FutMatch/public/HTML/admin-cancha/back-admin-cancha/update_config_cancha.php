<?php
require "../conexion.php";

$id_cancha = $_POST['id_cancha'];
$horaApertura = $_POST['horaApertura'];
$horaCierre = $_POST['horaCierre'];
$dias = $_POST['dias']; // array de ID de días

// Borrar horarios anteriores
$conn->prepare("DELETE FROM horarios_cancha WHERE id_cancha = ?")
     ->execute([$id_cancha]);

// Insertar nuevos horarios por cada día marcado
$stmt = $conn->prepare("
    INSERT INTO horarios_cancha (id_cancha, id_dia, hora_apertura, hora_cierre)
    VALUES (?, ?, ?, ?)
");

foreach ($dias as $id_dia) {
    $stmt->execute([$id_cancha, $id_dia, $horaApertura, $horaCierre]);
}

echo json_encode(["success" => true]);

