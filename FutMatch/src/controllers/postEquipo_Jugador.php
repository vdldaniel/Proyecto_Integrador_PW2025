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

$id_lider = $_SESSION['user_id'];

// Manejar la subida de foto
$rutaFoto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['foto'];

    // Validar tipo de archivo
    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($archivo['type'], $tiposPermitidos)) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['error' => 'Tipo de archivo no permitido. Solo se permiten imágenes.']);
        exit;
    }

    // Validar tamaño (máximo 5MB)
    $tamañoMaximo = 5 * 1024 * 1024; // 5MB en bytes
    if ($archivo['size'] > $tamañoMaximo) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['error' => 'El archivo es demasiado grande. Máximo 5MB.']);
        exit;
    }

    // Crear directorio si no existe
    $directorioDestino = __DIR__ . '/../../public/uploads/equipos/';
    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

    // Generar nombre único para el archivo
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('equipo_' . time() . '_') . '.' . $extension;
    $rutaCompleta = $directorioDestino . $nombreArchivo;

    // Mover archivo al directorio de uploads
    if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        $rutaFoto = 'uploads/equipos/' . $nombreArchivo;
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar el archivo']);
        exit;
    }
}

// Decodificar jugadores si viene como JSON string
$jugadores = [];
if (!empty($data['jugadores'])) {
    $jugadores = json_decode($data['jugadores'], true);
    if (!is_array($jugadores)) {
        $jugadores = [];
    }
}

try {
    //Inserto nuevo equipo
    $queryEquipo =
        'INSERT INTO equipos (id_lider, nombre, foto, abierto, descripcion, creado_por)
            VALUES (:id_lider, :nombre, :foto, :abierto, :descripcion, :creado_por)';

    $stmt = $conn->prepare($queryEquipo);
    $stmt->bindParam(':id_lider', $id_lider);
    $stmt->bindParam(':nombre', $data['nombre']);
    $stmt->bindParam(':foto', $rutaFoto);
    $stmt->bindParam(':abierto', $data['abierto']);
    $stmt->bindParam(':descripcion', $data['descripcion']);
    $stmt->bindParam(':creado_por', $id_lider);

    $stmt->execute();
    $id_equipo = $conn->lastInsertId();

    // Inserto al líder como miembro del equipo (estado_solicitud = 3: aceptado)
    $queryLider = 'INSERT INTO jugadores_equipos (id_jugador, id_equipo, estado_solicitud) 
                   VALUES (:id_jugador, :id_equipo, 3)';
    $stmtLider = $conn->prepare($queryLider);
    $stmtLider->bindParam(':id_jugador', $id_lider);
    $stmtLider->bindParam(':id_equipo', $id_equipo);
    $stmtLider->execute();

    // Inserto los jugadores invitados (estado_solicitud = 1: pendiente)
    if (!empty($jugadores)) {
        $queryJugadores = 'INSERT INTO jugadores_equipos (id_jugador, id_equipo, estado_solicitud, invitado_por) 
                           VALUES (:id_jugador, :id_equipo, 1, :invitado_por)';
        $stmtJugadores = $conn->prepare($queryJugadores);

        foreach ($jugadores as $id_jugador) {
            $stmtJugadores->bindParam(':id_jugador', $id_jugador);
            $stmtJugadores->bindParam(':id_equipo', $id_equipo);
            $stmtJugadores->bindParam(':invitado_por', $id_lider);
            $stmtJugadores->execute();
        }
    }

    // header json
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'id' => $id_equipo,
        'foto' => $rutaFoto,
        'message' => 'Equipo creado exitosamente'
    ]);
} catch (PDOException $e) {
    // Si hubo error, eliminar foto si se subió
    if ($rutaFoto && file_exists($directorioDestino . basename($rutaFoto))) {
        unlink($directorioDestino . basename($rutaFoto));
    }

    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al crear el equipo',
        'details' => $e->getMessage()
    ]);
}
