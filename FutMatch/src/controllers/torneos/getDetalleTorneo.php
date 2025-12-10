<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin_cancha', 'jugador'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'];

// Obtener ID del torneo desde GET
$idTorneo = $_GET['id_torneo'] ?? null;

if (!$idTorneo) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de torneo requerido"]);
    exit;
}

try {
    // Consultar datos del torneo
    // Para jugadores: solo mostrar torneos públicos
    // Para admin_cancha: verificar propiedad
    $sqlTorneo = "SELECT 
                    t.id_torneo,
                    t.nombre,
                    t.fecha_inicio,
                    t.fecha_fin,
                    t.descripcion,
                    t.max_equipos,
                    t.id_etapa,
                    e.nombre as etapa_nombre,
                    t.cierre_inscripciones,
                    (SELECT COUNT(*) FROM equipos_torneos WHERE id_torneo = t.id_torneo AND id_estado = 3) as equipos_registrados
                  FROM torneos t
                  INNER JOIN etapas_torneo e ON t.id_etapa = e.id_etapa
                  WHERE t.id_torneo = :id_torneo";

    // Si es admin_cancha, verificar propiedad
    if ($userType === 'admin_cancha') {
        $sqlTorneo .= " AND t.id_organizador = :id_organizador";
    }

    $stmtTorneo = $conn->prepare($sqlTorneo);
    $stmtTorneo->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    if ($userType === 'admin_cancha') {
        $stmtTorneo->bindValue(':id_organizador', $userId, PDO::PARAM_INT);
    }
    $stmtTorneo->execute();
    $torneo = $stmtTorneo->fetch(PDO::FETCH_ASSOC);

    if (!$torneo) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado"]);
        exit;
    }

    echo json_encode([
        "status" => "success",
        "data" => $torneo
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al cargar torneo: " . $e->getMessage()
    ]);
}
