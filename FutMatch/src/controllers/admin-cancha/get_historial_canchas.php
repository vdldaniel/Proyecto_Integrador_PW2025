<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

// Si no existe cache, devolver vacío
if (!isset($CANCHAS_CACHE) || empty($CANCHAS_CACHE)) {
    echo json_encode([]);
    exit;
}

$historial = [];


foreach ($CANCHAS_CACHE as $cancha) {

    // Si NO tiene historial no la agregamos
    if (!isset($cancha['historial_estados']) || empty($cancha['historial_estados'])) {
        continue;
    }

    // Detectar si hay estados conflictivos / cambios importantes
    $tieneCambios = false;

    foreach ($cancha['historial_estados'] as $h) {
        if ($h['estado'] !== "Disponible") {
            $tieneCambios = true;
            break;
        }
    }

    if ($tieneCambios) {
        
        $historial[] = [
            "id_cancha" => $cancha["id"],
            "nombre"    => $cancha["nombre"],
            "superficie"=> $cancha["tipo_superficie"] ?? '',
            "capacidad" => $cancha["capacidad"] ?? '',
            "historial" => $cancha["historial_estados"],
            "ultimo_estado" => end($cancha["historial_estados"])["estado"],
            "fecha_ultimo" => end($cancha["historial_estados"])["fecha"]
        ];
    }
}

echo json_encode($historial);
