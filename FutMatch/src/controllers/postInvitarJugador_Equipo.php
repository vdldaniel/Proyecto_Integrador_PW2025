<?php
require_once '../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener datos del POST
$data = $_POST;

// Verificar si se recibieron datos
if (empty($data)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'error' => 'No se recibieron datos',
    ]);
    exit;
}

// Verificar que se recibió el id_equipo
if (empty($data['id_equipo'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'error' => 'ID de equipo no proporcionado',
    ]);
    exit;
}

$id_equipo = $data['id_equipo'];
$id_invitador = $_SESSION['user_id'];

// Decodificar jugadores si viene como JSON string
$jugadores = [];
if (!empty($data['jugadores'])) {
    $jugadores = json_decode($data['jugadores'], true);
    if (!is_array($jugadores)) {
        $jugadores = [];
    }
}

// Verificar que hay jugadores para invitar
if (empty($jugadores)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'error' => 'No hay jugadores para invitar',
    ]);
    exit;
}

try {
    // Verificar que el usuario actual es miembro del equipo
    $queryVerificar = 'SELECT COUNT(*) FROM jugadores_equipos WHERE id_jugador = :id_jugador AND id_equipo = :id_equipo';
    $stmtVerificar = $conn->prepare($queryVerificar);
    $stmtVerificar->bindParam(':id_jugador', $id_invitador);
    $stmtVerificar->bindParam(':id_equipo', $id_equipo);
    $stmtVerificar->execute();

    if ($stmtVerificar->fetchColumn() == 0) {
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode([
            'error' => 'No tienes permiso para invitar jugadores a este equipo',
        ]);
        exit;
    }

    // Insertar los jugadores invitados
    $queryInvitar = 'INSERT INTO jugadores_equipos (id_jugador, id_equipo, estado_solicitud, invitado_por) 
                     VALUES (:id_jugador, :id_equipo, 1, :invitado_por)';
    $stmtInvitar = $conn->prepare($queryInvitar);

    $jugadoresInvitados = 0;
    $jugadoresPendientes = [];

    foreach ($jugadores as $id_jugador) {
        // Verificar si el jugador ya está en el equipo o tiene invitación pendiente
        $queryExiste = 'SELECT estado_solicitud FROM jugadores_equipos 
                        WHERE id_jugador = :id_jugador AND id_equipo = :id_equipo';
        $stmtExiste = $conn->prepare($queryExiste);
        $stmtExiste->bindParam(':id_jugador', $id_jugador);
        $stmtExiste->bindParam(':id_equipo', $id_equipo);
        $stmtExiste->execute();
        $resultado = $stmtExiste->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            // No existe, agregar nueva invitación
            $stmtInvitar->bindParam(':id_jugador', $id_jugador);
            $stmtInvitar->bindParam(':id_equipo', $id_equipo);
            $stmtInvitar->bindParam(':invitado_por', $id_invitador);
            $stmtInvitar->execute();
            $jugadoresInvitados++;
        } elseif ($resultado['estado_solicitud'] == 1 || $resultado['estado_solicitud'] == 2) {
            // Solicitud pendiente o en revisión
            $jugadoresPendientes[] = $id_jugador;
        }
        // Si estado_solicitud es 3 (aceptada), ya es miembro, no hacer nada
        // Si es 4 o 5 (rechazada/cancelada), podrían reinvitarse pero por ahora no lo permitimos
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => "Se invitaron $jugadoresInvitados jugador(es) exitosamente",
        'jugadores_invitados' => $jugadoresInvitados,
        'jugadores_pendientes' => $jugadoresPendientes
    ]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al invitar jugadores',
        'details' => $e->getMessage()
    ]);
}
