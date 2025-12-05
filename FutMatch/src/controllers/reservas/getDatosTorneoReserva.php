<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

// Obtener ID de la reserva desde GET
$idReserva = $_GET['id_reserva'] ?? null;

if (!$idReserva) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de reserva requerido"]);
    exit;
}

try {
    // Consulta para obtener datos del torneo asociado a la reserva
    $sql = "SELECT 
                r.id_reserva,
                p.id_partido,
                pt.id_torneo,
                pt.id_fase,
                pt.orden_en_fase,
                pt.id_equipo_A,
                pt.id_equipo_B,
                t.nombre as nombre_torneo,
                t.descripcion as descripcion_torneo,
                t.fecha_inicio,
                t.fecha_fin,
                t.max_equipos,
                ft.nombre as fase_nombre,
                eA.nombre as equipo_a_nombre,
                eB.nombre as equipo_b_nombre,
                p.goles_equipo_A,
                p.goles_equipo_B
            FROM reservas r
            INNER JOIN partidos p ON r.id_reserva = p.id_reserva
            INNER JOIN partidos_torneos pt ON p.id_partido = pt.id_partido
            INNER JOIN torneos t ON pt.id_torneo = t.id_torneo
            INNER JOIN fases_torneo ft ON pt.id_fase = ft.id_fase
            LEFT JOIN equipos eA ON pt.id_equipo_A = eA.id_equipo
            LEFT JOIN equipos eB ON pt.id_equipo_B = eB.id_equipo
            WHERE r.id_reserva = :id_reserva";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_reserva', $idReserva, PDO::PARAM_INT);
    $stmt->execute();
    $datosTorneo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datosTorneo) {
        // No es una reserva de torneo, devolver respuesta vacía sin error
        echo json_encode([
            "status" => "success",
            "es_torneo" => false,
            "datos" => null
        ]);
        exit;
    }

    echo json_encode([
        "status" => "success",
        "es_torneo" => true,
        "datos" => $datosTorneo
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al cargar datos de torneo: " . $e->getMessage()
    ]);
}
