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

// fotos
$rutaFoto = null;
$foto = isset($data['foto']) ? $data['foto'] : null;
$tipoFoto = isset($data['tipoFoto']) ? $data['tipoFoto'] : null;

// valido el tipo de foto
if ($tipoFoto !== 'fotoPerfil' && $tipoFoto !== 'banner') {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de foto no válido']);
    exit;
}

$id_jugador = $_SESSION['user_id'];

// hacemos validaciones de la foto subida
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

    // Crear directorio si no existe
    $directorioDestino = __DIR__ . '/../../public/uploads/jugadores/';
    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

    // Generar nombre único para el archivo
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('jugador_' . time() . '_') . '.' . $extension;
    $rutaCompleta = $directorioDestino . $nombreArchivo;

    // Mover archivo al directorio de uploads
    if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        $rutaFoto = 'uploads/jugadores/' . $nombreArchivo;
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar el archivo']);
        exit;
    }
}


try {
    if ($tipoFoto === 'fotoPerfil') {
        $queryJugador =
            'UPDATE jugadores SET 
                foto_perfil = :foto_perfil
             WHERE id_jugador = :id_jugador';

        $stmt = $conn->prepare($queryJugador);
        $stmt->bindParam(':foto_perfil', $rutaFoto);
    } elseif ($tipoFoto === 'banner') {
        $queryJugador =
            'UPDATE jugadores SET 
                banner = :foto_portada
             WHERE id_jugador = :id_jugador';

        $stmt = $conn->prepare($queryJugador);
        $stmt->bindParam(':foto_portada', $rutaFoto);
    }
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    // header json
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'foto' => $rutaFoto,
        'message' => 'Foto cambiada exitosamente'
    ]);
} catch (PDOException $e) {
    // Si hubo error, eliminar foto si se subió
    if ($rutaFoto && file_exists($directorioDestino . basename($rutaFoto))) {
        unlink($directorioDestino . basename($rutaFoto));
    }

    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al subir la foto',
        'details' => $e->getMessage()
    ]);
}
