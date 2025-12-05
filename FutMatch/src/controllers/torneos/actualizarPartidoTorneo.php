<?php

/**
 * Controller: Actualizar Resultado de Partido de Torneo
 * 
 * Actualiza los goles de un partido de torneo
 * 
 * Método: POST
 * Body JSON: {
 *   id_partido: number,
 *   goles_equipo_A: number,
 *   goles_equipo_B: number
 * }
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../app/config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin_cancha') {
    echo json_encode([
        'status' => 'error',
        'message' => 'No autorizado'
    ]);
    exit;
}

try {
    // Obtener datos JSON del body
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Datos inválidos');
    }

    $idPartido = $input['id_partido'] ?? null;
    $golesEquipoA = $input['goles_equipo_A'] ?? null;
    $golesEquipoB = $input['goles_equipo_B'] ?? null;

    // Validaciones
    if (!$idPartido || $golesEquipoA === null || $golesEquipoB === null) {
        throw new Exception('Faltan datos requeridos');
    }

    if (!is_numeric($golesEquipoA) || !is_numeric($golesEquipoB)) {
        throw new Exception('Los goles deben ser números válidos');
    }

    if ($golesEquipoA < 0 || $golesEquipoB < 0) {
        throw new Exception('Los goles no pueden ser negativos');
    }

    // Verificar que el partido existe y obtener información
    $stmtVerify = $conn->prepare("
        SELECT p.id_partido, p.id_reserva, pt.id_torneo, t.id_organizador
        FROM partidos p
        INNER JOIN partidos_torneos pt ON p.id_partido = pt.id_partido
        INNER JOIN torneos t ON pt.id_torneo = t.id_torneo
        WHERE p.id_partido = :id_partido
    ");
    $stmtVerify->execute([':id_partido' => $idPartido]);
    $partido = $stmtVerify->fetch(PDO::FETCH_ASSOC);

    if (!$partido) {
        throw new Exception('Partido no encontrado');
    }

    // Verificar que el partido tiene una reserva (está programado)
    if (!$partido['id_reserva']) {
        throw new Exception('El partido debe estar programado antes de registrar resultados');
    }

    // Verificar que el admin_cancha es el dueño del torneo
    if ($partido['id_organizador'] != $_SESSION['user_id']) {
        throw new Exception('No tiene permisos para actualizar este partido');
    }

    // Actualizar los goles del partido
    $stmtUpdate = $conn->prepare("
        UPDATE partidos 
        SET goles_equipo_A = :goles_equipo_A, 
            goles_equipo_B = :goles_equipo_B
        WHERE id_partido = :id_partido
    ");

    $stmtUpdate->execute([
        ':goles_equipo_A' => (int)$golesEquipoA,
        ':goles_equipo_B' => (int)$golesEquipoB,
        ':id_partido' => $idPartido
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Resultado actualizado exitosamente',
        'data' => [
            'id_partido' => $idPartido,
            'goles_equipo_A' => (int)$golesEquipoA,
            'goles_equipo_B' => (int)$golesEquipoB
        ]
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
