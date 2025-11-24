<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

try {
    if (!isset($_GET['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "ID no recibido"
        ]);
        exit;
    }

    $id = intval($_GET['id']);

    //datos generales de la cancha
    $sql1 = "
        SELECT 
            c.id_cancha,
            c.nombre,
            c.descripcion,
            c.banner,
            c.id_estado,
            c.id_superficie,
            d.direccion_completa,
            d.latitud,
            d.longitud,
            s.nombre AS superficie_nombre
        FROM canchas c
        INNER JOIN direcciones d ON d.id_direccion = c.id_direccion
        INNER JOIN superficies_canchas s ON s.id_superficie = c.id_superficie
        WHERE c.id_cancha = :id
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql1);
    $stmt->execute(['id' => $id]);
    $cancha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cancha) {
        echo json_encode(["status" => "error", "message" => "Cancha no encontrada"]);
        exit;
    }

    // tipos de partido admitidos
    $sql2 = "
        SELECT 
            tp.id_tipo_partido,
            tp.nombre,
            tp.min_participantes,
            tp.max_participantes
        FROM canchas_tipos_partido ctp
        INNER JOIN tipos_partido tp 
            ON tp.id_tipo_partido = ctp.id_tipo_partido
        WHERE ctp.id_cancha = :id AND ctp.activo = 1
    ";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute(['id' => $id]);
    $tipos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    //horarios de la cancha
    $sql3 = "
        SELECT 
            ds.nombre AS dia_nombre,
            hc.id_dia, 
            hc.hora_apertura,
            hc.hora_cierre
        FROM horarios_cancha hc
        INNER JOIN dias_semana ds ON ds.id_dia = hc.id_dia
        WHERE hc.id_cancha = :id
        ORDER BY hc.id_dia
    ";

    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute(['id' => $id]);
    $horarios = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    //servicios de la cancha (NUEVO)
    $sql4 = "
        SELECT 
            s.id_servicio,
            s.nombre AS servicio_nombre
        FROM servicios_canchas sc
        INNER JOIN servicios s ON s.id_servicio = sc.id_servicio
        WHERE sc.id_cancha = :id
    ";

    $stmt4 = $conn->prepare($sql4);
    $stmt4->execute(['id' => $id]);
    $servicios = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    // Respuesta final unificada
    echo json_encode([
        "status" => "success",
        "data" => [
            "cancha" => $cancha,
            "tipos_partido" => $tipos,
            "horarios" => $horarios,
            "servicios" => $servicios // INCLUIDO
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}