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
$id_usuario = $_SESSION['user_id'];

// Verificar que el usuario es el líder del equipo
$queryVerificar = 'SELECT id_lider FROM equipos WHERE id_equipo = :id_equipo';
$stmtVerificar = $conn->prepare($queryVerificar);
$stmtVerificar->bindParam(':id_equipo', $id_equipo);
$stmtVerificar->execute();
$equipo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

if (!$equipo) {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(['error' => 'Equipo no encontrado']);
    exit;
}

if ($equipo['id_lider'] != $id_usuario) {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode(['error' => 'Solo el líder puede modificar el equipo']);
    exit;
}

// Manejar la subida de foto
$rutaFoto = null;
$actualizarFoto = false;

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
        $actualizarFoto = true;
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
    // Verificar si se trata de eliminar un jugador específico
    if (!empty($data['eliminar_jugador']) && $data['eliminar_jugador'] == '1') {
        if (empty($data['id_jugador'])) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['error' => 'ID de jugador no proporcionado']);
            exit;
        }

        $id_jugador_eliminar = $data['id_jugador'];

        // No permitir eliminar al líder
        if ($id_jugador_eliminar == $equipo['id_lider']) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['error' => 'No se puede eliminar al líder del equipo']);
            exit;
        }

        $queryEliminarJugador = 'DELETE FROM jugadores_equipos 
                                 WHERE id_equipo = :id_equipo AND id_jugador = :id_jugador';
        $stmtEliminarJugador = $conn->prepare($queryEliminarJugador);
        $stmtEliminarJugador->bindParam(':id_equipo', $id_equipo);
        $stmtEliminarJugador->bindParam(':id_jugador', $id_jugador_eliminar);
        $stmtEliminarJugador->execute();

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Jugador eliminado exitosamente'
        ]);
        exit;
    }

    // Manejar la subida de foto
    $rutaFoto = null;
    $actualizarFoto = false;

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
            $actualizarFoto = true;
        } else {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el archivo']);
            exit;
        }
    }

    // Verificar si se debe eliminar la foto
    $eliminarFoto = !empty($data['eliminar_foto']) && $data['eliminar_foto'] == '1';

    // Validar que existan los campos requeridos para actualización de equipo
    if (empty($data['nombre'])) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['error' => 'Nombre del equipo es requerido']);
        exit;
    }

    // Actualizar información del equipo
    if ($actualizarFoto) {
        $queryEquipo = 'UPDATE equipos 
                        SET nombre = :nombre, descripcion = :descripcion, foto = :foto 
                        WHERE id_equipo = :id_equipo';
    } elseif ($eliminarFoto) {
        $queryEquipo = 'UPDATE equipos 
                        SET nombre = :nombre, descripcion = :descripcion, foto = NULL 
                        WHERE id_equipo = :id_equipo';
    } else {
        $queryEquipo = 'UPDATE equipos 
                        SET nombre = :nombre, descripcion = :descripcion 
                        WHERE id_equipo = :id_equipo';
    }

    $stmtEquipo = $conn->prepare($queryEquipo);
    $stmtEquipo->bindParam(':nombre', $data['nombre']);
    $stmtEquipo->bindParam(':descripcion', $data['descripcion']);
    $stmtEquipo->bindParam(':id_equipo', $id_equipo);

    if ($actualizarFoto) {
        $stmtEquipo->bindParam(':foto', $rutaFoto);
    }

    $stmtEquipo->execute();

    // Actualizar jugadores del equipo
    // Primero, obtener jugadores actuales (excepto el líder)
    $queryActuales = 'SELECT id_jugador FROM jugadores_equipos 
                      WHERE id_equipo = :id_equipo AND id_jugador != :id_lider';
    $stmtActuales = $conn->prepare($queryActuales);
    $stmtActuales->bindParam(':id_equipo', $id_equipo);
    $stmtActuales->bindParam(':id_lider', $equipo['id_lider']);
    $stmtActuales->execute();
    $jugadoresActuales = $stmtActuales->fetchAll(PDO::FETCH_COLUMN);

    // Eliminar jugadores que ya no están en la lista
    $jugadoresAEliminar = array_diff($jugadoresActuales, $jugadores);
    if (!empty($jugadoresAEliminar)) {
        $placeholders = implode(',', array_fill(0, count($jugadoresAEliminar), '?'));
        $queryEliminar = "DELETE FROM jugadores_equipos 
                          WHERE id_equipo = ? AND id_jugador IN ($placeholders)";
        $stmtEliminar = $conn->prepare($queryEliminar);
        $stmtEliminar->execute(array_merge([$id_equipo], $jugadoresAEliminar));
    }

    // Agregar nuevos jugadores (estado_solicitud = 1: pendiente)
    $jugadoresAAgregar = array_diff($jugadores, $jugadoresActuales);
    if (!empty($jugadoresAAgregar)) {
        $queryAgregar = 'INSERT INTO jugadores_equipos (id_jugador, id_equipo, estado_solicitud, invitado_por) 
                         VALUES (:id_jugador, :id_equipo, 1, :invitado_por)';
        $stmtAgregar = $conn->prepare($queryAgregar);

        foreach ($jugadoresAAgregar as $id_jugador) {
            $stmtAgregar->bindParam(':id_jugador', $id_jugador);
            $stmtAgregar->bindParam(':id_equipo', $id_equipo);
            $stmtAgregar->bindParam(':invitado_por', $id_usuario);
            $stmtAgregar->execute();
        }
    }

    // Transferir liderazgo si se especificó nuevo líder
    if (!empty($data['nuevo_lider'])) {
        $nuevo_lider = intval($data['nuevo_lider']); // Convertir a entero

        // Verificar que el nuevo líder es miembro del equipo con estado aceptado
        $queryVerificarMiembro = 'SELECT COUNT(*) FROM jugadores_equipos 
                                  WHERE id_jugador = :id_jugador 
                                  AND id_equipo = :id_equipo 
                                  AND estado_solicitud = 3';
        $stmtVerificarMiembro = $conn->prepare($queryVerificarMiembro);
        $stmtVerificarMiembro->bindParam(':id_jugador', $nuevo_lider, PDO::PARAM_INT);
        $stmtVerificarMiembro->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
        $stmtVerificarMiembro->execute();

        if ($stmtVerificarMiembro->fetchColumn() > 0) {
            $queryTransferir = 'UPDATE equipos SET id_lider = :nuevo_lider WHERE id_equipo = :id_equipo';
            $stmtTransferir = $conn->prepare($queryTransferir);
            $stmtTransferir->bindParam(':nuevo_lider', $nuevo_lider, PDO::PARAM_INT);
            $stmtTransferir->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
            $stmtTransferir->execute();
        } else {
            // Log para debugging
            error_log("No se pudo transferir liderazgo. Jugador $nuevo_lider no es miembro aceptado del equipo $id_equipo");
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Equipo actualizado exitosamente',
        'foto' => $rutaFoto
    ]);
} catch (PDOException $e) {
    // Si hubo error, eliminar foto si se subió
    if ($actualizarFoto && $rutaFoto) {
        $directorioDestino = __DIR__ . '/../../public/uploads/equipos/';
        if (file_exists($directorioDestino . basename($rutaFoto))) {
            unlink($directorioDestino . basename($rutaFoto));
        }
    }

    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al actualizar el equipo',
        'details' => $e->getMessage()
    ]);
}
