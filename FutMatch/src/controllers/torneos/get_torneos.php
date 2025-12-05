<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Validar sesión
$idAdminCancha = $_SESSION['user_id'] ?? null;

if (!$idAdminCancha) {
    echo json_encode([
        "status" => "error",
        "message" => "Usuario no autenticado"
    ]);
    exit;
}

try {

    // ------------------------------
    // 1) Obtener lista de torneos
    // ------------------------------
    $sql = "
        SELECT
            t.id_torneo,
            t.nombre,
            t.descripcion,
            t.fecha_inicio,
            t.fecha_fin,
            t.fin_estimativo,
            t.id_etapa,
            t.max_equipos
        FROM torneos t
        WHERE t.id_organizador = :idAdminCancha
        ORDER BY t.id_torneo DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":idAdminCancha", $idAdminCancha, PDO::PARAM_INT);
    $stmt->execute();
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$torneos) {
        echo json_encode([
            "status" => "success",
            "data" => []
        ]);
        exit;
    }

    // ------------------------------
    // 2) Construir respuesta detallada por torneo
    // ------------------------------
    $respuesta = [];

    foreach ($torneos as $torneo) {

        $idTorneo = $torneo['id_torneo'];

        // 2.1) Cantidad de equipos inscriptos
        $sqlEquipos = "
            SELECT COUNT(*) AS total
            FROM equipos_torneos
            WHERE id_torneo = :idTorneo
        ";
        $stmtEquipos = $conn->prepare($sqlEquipos);
        $stmtEquipos->execute(["idTorneo" => $idTorneo]);
        $totalEquipos = $stmtEquipos->fetch(PDO::FETCH_ASSOC)['total'];

        // 2.2) Cantidad de partidos totales del torneo
        $sqlPartidosTotales = "
            SELECT COUNT(*) AS total
            FROM partidos_torneos
            WHERE id_torneo = :idTorneo
        ";
        $stmtPT = $conn->prepare($sqlPartidosTotales);
        $stmtPT->execute(["idTorneo" => $idTorneo]);
        $partidosTotales = $stmtPT->fetch(PDO::FETCH_ASSOC)['total'];

        // 2.3) Cantidad de partidos ya jugados
        $sqlPartidosJugados = "
            SELECT COUNT(*) AS jugados
            FROM partidos_torneos pt
            INNER JOIN partidos p ON pt.id_partido = p.id_partido
            WHERE pt.id_torneo = :idTorneo
              AND p.goles_equipo_A IS NOT NULL
              AND p.goles_equipo_B IS NOT NULL
        ";
        $stmtPJ = $conn->prepare($sqlPartidosJugados);
        $stmtPJ->execute(["idTorneo" => $idTorneo]);
        $partidosJugados = $stmtPJ->fetch(PDO::FETCH_ASSOC)['jugados'];

        // 2.4) Calcular cupos disponibles
        $cuposDisponibles = $torneo["max_equipos"] - $totalEquipos;

        // 2.5) Convertir etapa en estado textual
        $estado = "próximamente";
        if ($torneo["id_etapa"] == 1) $estado = "inscripciones abiertas";
        if ($torneo["id_etapa"] == 2) $estado = "en curso";
        if ($torneo["id_etapa"] == 3) $estado = "finalizado";

        // 2.6) Agregar datos al array final
        $respuesta[] = [
            "id_torneo"        => $idTorneo,
            "nombre"           => $torneo["nombre"],
            "descripcion"      => $torneo["descripcion"],
            "fecha_inicio"     => $torneo["fecha_inicio"],
            "fecha_fin"        => $torneo["fecha_fin"],
            "fin_estimativo"   => $torneo["fin_estimativo"],
            "max_equipos"      => $torneo["max_equipos"],
            "equipos_inscriptos" => $totalEquipos,
            "cupos_disponibles" => $cuposDisponibles,
            "partidos_totales"  => $partidosTotales,
            "partidos_jugados"  => $partidosJugados,
            "estado"            => $estado
        ];
    }

    echo json_encode([
        "status" => "success",
        "data" => $respuesta
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
