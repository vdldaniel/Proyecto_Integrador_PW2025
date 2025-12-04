<?php
// Configurar manejo de errores para evitar que se muestren en la salida
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Iniciar buffer de salida para evitar cualquier output antes del JSON
ob_start();

try {
    require_once '../../app/config.php';
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Error al cargar configuración', 'detalle' => $e->getMessage()]);
    exit();
}

// Limpiar cualquier output generado por config.php
ob_clean();

// Establecer header JSON PRIMERO para evitar errores de formato
header('Content-Type: application/json');

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

// Log de debug: datos recibidos y sesión
error_log("POST_RESERVA - Inicio: user_id=" . $_SESSION['user_id'] . ", user_type=" . ($_SESSION['user_type'] ?? 'null'));
error_log("POST_RESERVA - Datos POST: " . json_encode($data));

// Verificar si se recibieron datos
if (empty($data)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'No se recibieron datos',
    ]);
    exit;
}

$id_usuario = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'] ?? null;


// Campos de la reserva
$id_cancha = isset($data['id_cancha']) ? trim($data['id_cancha']) : null;
$id_tipo_reserva = isset($data['id_tipo_reserva']) ? trim($data['id_tipo_reserva']) : null;
$fecha = isset($data['fecha']) ? trim($data['fecha']) : null;
$fecha_fin = isset($data['fecha_fin']) ? trim($data['fecha_fin']) : $fecha; // Por defecto igual a fecha
$hora_inicio = isset($data['hora_inicio']) ? trim($data['hora_inicio']) : null;
$hora_fin = isset($data['hora_fin']) ? trim($data['hora_fin']) : null;
$titulo = isset($data['titulo']) ? trim($data['titulo']) : null;
$descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : null;

// Campos opcionales para reserva externa o con jugador
$username = isset($data['username']) ? trim($data['username']) : null;
$reserva_externa = isset($data['reserva_externa']) ? (bool)$data['reserva_externa'] : false;
$nombre_externo = isset($data['nombre_externo']) ? trim($data['nombre_externo']) : null;
$telefono_externo = isset($data['telefono_externo']) ? trim($data['telefono_externo']) : null;

// Log de depuración para verificar qué datos llegan
error_log("POST_RESERVA - Datos recibidos: username='$username', reserva_externa=" . ($reserva_externa ? 'true' : 'false') . ", nombre_externo='$nombre_externo', telefono_externo='$telefono_externo'");

// Validar campos requeridos
if (empty($id_cancha) || empty($id_tipo_reserva) || empty($fecha) || empty($hora_inicio) || empty($hora_fin)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Faltan campos requeridos',
        'detalles' => [
            'id_cancha' => empty($id_cancha) ? 'requerido' : 'ok',
            'id_tipo_reserva' => empty($id_tipo_reserva) ? 'requerido' : 'ok',
            'fecha' => empty($fecha) ? 'requerido' : 'ok',
            'hora_inicio' => empty($hora_inicio) ? 'requerido' : 'ok',
            'hora_fin' => empty($hora_fin) ? 'requerido' : 'ok'
        ]
    ]);
    exit();
}

// Validar la cancha según el tipo de usuario
try {
    if ($user_type === 'admin_cancha') {
        // Admin: Validar que la cancha le pertenece
        $queryCancha = "SELECT id_cancha FROM canchas WHERE id_cancha = :id_cancha AND id_admin_cancha = :id_usuario";
        $stmtCancha = $conn->prepare($queryCancha);
        $stmtCancha->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
        $stmtCancha->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtCancha->execute();

        if (!$stmtCancha->fetch()) {
            http_response_code(403);
            echo json_encode(['error' => 'No tienes permiso para crear reservas en esta cancha']);
            exit();
        }
    } elseif ($user_type === 'jugador') {
        // Jugador: Solo verificar que la cancha existe
        $queryCancha = "SELECT id_cancha FROM canchas WHERE id_cancha = :id_cancha";
        $stmtCancha = $conn->prepare($queryCancha);
        $stmtCancha->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
        $stmtCancha->execute();

        if (!$stmtCancha->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Cancha no encontrada']);
            exit();
        }

        // Para jugadores: El titular de la reserva ES el jugador que la crea
        // No necesitamos buscar username, usamos directamente el id_usuario
        // Esto se manejará más adelante en el código
        error_log("POST_RESERVA - Jugador creando reserva: id_usuario=$id_usuario, user_type=$user_type");
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Tipo de usuario no autorizado para crear reservas']);
        exit();
    }
} catch (PDOException $e) {
    error_log("POST_RESERVA - ERROR VALIDAR CANCHA: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al validar la cancha']);
    exit();
}

// Determinar titular de la reserva
$id_titular_jugador = null;
$id_titular_externo = null;
$nombre_completo = null;
$telefono = null;

// LÓGICA SIMPLIFICADA:
// 1. Si user_type = 'jugador': id_titular_jugador = id_creador_usuario = user_id
// 2. Si admin + username: Buscar jugador por username
// 3. Si admin + reserva_externa: Crear persona externa

if ($user_type === 'jugador') {
    // JUGADOR: El titular es el mismo jugador que crea la reserva
    $id_titular_jugador = $id_usuario;

    // Obtener datos del jugador para el log
    try {
        $queryJugador = "SELECT 
            u.nombre,
            u.apellido,
            j.telefono
        FROM jugadores j
        INNER JOIN usuarios u ON j.id_jugador = u.id_usuario
        WHERE j.id_jugador = :id_jugador";

        $stmtJugador = $conn->prepare($queryJugador);
        $stmtJugador->bindParam(':id_jugador', $id_usuario, PDO::PARAM_INT);
        $stmtJugador->execute();
        $jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);

        if ($jugador) {
            $nombre_completo = $jugador['nombre'] . ' ' . $jugador['apellido'];
            $telefono = $jugador['telefono'];
        }
    } catch (PDOException $e) {
        error_log("POST_RESERVA - ERROR OBTENER DATOS JUGADOR: " . $e->getMessage());
    }

    error_log("POST_RESERVA - Jugador creando reserva para sí mismo: id_jugador=$id_titular_jugador, nombre='$nombre_completo'");
} elseif ($user_type === 'admin_cancha' && !empty($username)) {
    // ADMIN: Reserva para un jugador de la app (USUARIO REGISTRADO)
    try {
        $queryJugador = "SELECT 
            j.id_jugador,
            u.nombre,
            u.apellido,
            j.telefono
        FROM jugadores j
        INNER JOIN usuarios u ON j.id_jugador = u.id_usuario
        WHERE j.username = :username";

        $stmtJugador = $conn->prepare($queryJugador);
        $stmtJugador->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtJugador->execute();
        $jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);

        if ($jugador) {
            $id_titular_jugador = $jugador['id_jugador'];
            $nombre_completo = $jugador['nombre'] . ' ' . $jugador['apellido'];
            $telefono = $jugador['telefono'];

            error_log("POST_RESERVA - Admin creando reserva para jugador: username='$username', id_jugador=$id_titular_jugador, nombre='$nombre_completo'");
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            exit();
        }
    } catch (PDOException $e) {
        error_log("POST_RESERVA - ERROR BUSCAR JUGADOR: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error al buscar el jugador']);
        exit();
    }
} elseif ($user_type === 'admin_cancha' && $reserva_externa && empty($username)) {
    // Reserva para una PERSONA EXTERNA (no registrada en la app)
    // Solo entra aquí si NO hay username Y el checkbox está marcado
    // Validar que al menos haya nombre
    if (empty($nombre_externo)) {
        http_response_code(400);
        echo json_encode(['error' => 'Para reservas externas, el nombre completo es requerido']);
        exit();
    }

    // Validar que el teléfono contenga al menos un dígito (puede tener símbolos)
    if (!empty($telefono_externo) && !preg_match('/\d/', $telefono_externo)) {
        http_response_code(400);
        echo json_encode([
            'error' => 'El teléfono debe contener al menos un número',
            'detalles' => 'Verifique que no haya ingresado texto en el campo de teléfono'
        ]);
        exit();
    }

    // Preparar datos de persona externa (el INSERT se hará dentro de la transacción después de validar superposiciones)
    // Separar nombre completo en nombre y apellido
    $partes_nombre = explode(' ', trim($nombre_externo), 2);
    $nombre_externo_parsed = $partes_nombre[0];
    $apellido_externo_parsed = isset($partes_nombre[1]) ? $partes_nombre[1] : '';

    // Variables para usar después
    $id_titular_externo = null; // Se asignará después del INSERT
    $nombre_completo = $nombre_externo;
    $telefono = $telefono_externo;

    error_log("POST_RESERVA - Persona externa detectada: nombre='$nombre_completo', telefono='$telefono'");
} else {
    // Caso no manejado: admin sin username ni reserva_externa
    if ($user_type === 'admin_cancha') {
        error_log("POST_RESERVA - ERROR ADMIN: No se proporcionó username ni datos de persona externa");
        http_response_code(400);
        echo json_encode(['error' => 'Debe proporcionar un username (usuario registrado) o marcar como reserva externa con nombre']);
        exit();
    }
    // Para jugadores este else no debería ejecutarse nunca (ya se maneja arriba)
}

// Validar que hora_fin sea posterior a hora_inicio
if ($hora_fin <= $hora_inicio) {
    http_response_code(400);
    echo json_encode(['error' => 'La hora de fin debe ser posterior a la hora de inicio']);
    exit();
}

// Validar superposición de reservas (ahora la tabla SÍ tiene fecha_fin)
try {
    $querySuperposicion = "SELECT 
        r.id_reserva,
        r.fecha,
        r.fecha_fin,
        r.hora_inicio,
        r.hora_fin,
        r.titulo
    FROM reservas r
    WHERE r.id_cancha = :id_cancha
        AND r.id_estado = 3  -- Solo reservas activas
        AND (
            -- Las fechas se superponen Y las horas se superponen
            (
                -- Caso 1: Las fechas se superponen
                (
                    (:fecha BETWEEN r.fecha AND r.fecha_fin)
                    OR (:fecha_fin BETWEEN r.fecha AND r.fecha_fin)
                    OR (:fecha <= r.fecha AND :fecha_fin >= r.fecha_fin)
                )
                AND
                -- Caso 2: Las horas se superponen
                (
                    (:hora_inicio >= r.hora_inicio AND :hora_inicio < r.hora_fin)
                    OR (:hora_fin > r.hora_inicio AND :hora_fin <= r.hora_fin)
                    OR (:hora_inicio <= r.hora_inicio AND :hora_fin >= r.hora_fin)
                )
            )
        )";

    $stmtSuperposicion = $conn->prepare($querySuperposicion);
    $stmtSuperposicion->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtSuperposicion->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmtSuperposicion->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $stmtSuperposicion->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmtSuperposicion->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmtSuperposicion->execute();

    $reservasSuperpuestas = $stmtSuperposicion->fetchAll(PDO::FETCH_ASSOC);

    if (count($reservasSuperpuestas) > 0) {
        $detallesSuperposicion = [];
        foreach ($reservasSuperpuestas as $reserva) {
            $detallesSuperposicion[] = [
                'id_reserva' => $reserva['id_reserva'],
                'titulo' => $reserva['titulo'] ?: 'Sin título',
                'fecha' => $reserva['fecha'],
                'fecha_fin' => $reserva['fecha_fin'],
                'hora_inicio' => substr($reserva['hora_inicio'], 0, 5),
                'hora_fin' => substr($reserva['hora_fin'], 0, 5)
            ];
        }

        http_response_code(409); // Conflict
        echo json_encode([
            'error' => 'La reserva se superpone con otras reservas existentes',
            'superposiciones' => $detallesSuperposicion
        ]);
        exit();
    }
} catch (PDOException $e) {
    error_log("POST_RESERVA - ERROR VALIDAR SUPERPOSICION: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al validar superposición de reservas']);
    exit();
}

// Determinar el estado inicial de la reserva
// Jugadores: Pendiente (1) - requiere aprobación del admin
// Admin: Confirmada (3) - aprobación automática
$id_estado_inicial = ($user_type === 'jugador') ? 1 : 3;

// Insertar la reserva
try {
    $conn->beginTransaction();

    // Si es reserva externa, insertar la persona externa primero (dentro de la transacción)
    // IMPORTANTE: Solo insertar si NO hay id_titular_jugador (validación de seguridad)
    if ($reserva_externa && $id_titular_externo === null && $id_titular_jugador === null) {
        error_log("POST_RESERVA - Insertando persona externa: nombre='$nombre_externo_parsed', apellido='$apellido_externo_parsed', telefono='$telefono_externo'");

        $queryInsertExterno = "INSERT INTO personas_externas (nombre, apellido, telefono) 
                               VALUES (:nombre, :apellido, :telefono)";
        $stmtInsertExterno = $conn->prepare($queryInsertExterno);
        $stmtInsertExterno->bindParam(':nombre', $nombre_externo_parsed, PDO::PARAM_STR);
        $stmtInsertExterno->bindParam(':apellido', $apellido_externo_parsed, PDO::PARAM_STR);
        $telefono_externo_value = $telefono_externo ?: '';
        $stmtInsertExterno->bindParam(':telefono', $telefono_externo_value, PDO::PARAM_STR);
        $stmtInsertExterno->execute();

        // Obtener el ID de la persona externa recién insertada
        $id_titular_externo = $conn->lastInsertId();
        error_log("POST_RESERVA - Persona externa insertada con ID: $id_titular_externo");
    }

    // La tabla reservas tiene: id_cancha, id_tipo_reserva, fecha, fecha_fin, hora_inicio, hora_fin, titulo, descripcion, 
    // id_estado, id_creador_usuario, id_titular_jugador, id_titular_externo
    $queryInsert = "INSERT INTO reservas 
        (id_cancha, id_tipo_reserva, fecha, fecha_fin, hora_inicio, hora_fin, titulo, descripcion, 
         id_estado, id_creador_usuario, id_titular_jugador, id_titular_externo) 
        VALUES 
        (:id_cancha, :id_tipo_reserva, :fecha, :fecha_fin, :hora_inicio, :hora_fin, :titulo, :descripcion, 
         :id_estado, :id_creador_usuario, :id_titular_jugador, :id_titular_externo)";

    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtInsert->bindParam(':id_tipo_reserva', $id_tipo_reserva, PDO::PARAM_INT);
    $stmtInsert->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmtInsert->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $stmtInsert->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmtInsert->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmtInsert->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmtInsert->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmtInsert->bindParam(':id_estado', $id_estado_inicial, PDO::PARAM_INT);
    $stmtInsert->bindParam(':id_creador_usuario', $id_usuario, PDO::PARAM_INT);

    // Bind condicional para titular (uno será null)
    if ($id_titular_jugador !== null) {
        $stmtInsert->bindParam(':id_titular_jugador', $id_titular_jugador, PDO::PARAM_INT);
        $stmtInsert->bindValue(':id_titular_externo', null, PDO::PARAM_NULL);
    } else {
        $stmtInsert->bindValue(':id_titular_jugador', null, PDO::PARAM_NULL);
        $stmtInsert->bindParam(':id_titular_externo', $id_titular_externo, PDO::PARAM_INT);
    }

    $stmtInsert->execute();
    $id_reserva = $conn->lastInsertId();

    $queryTipoPartido = "SELECT id_tipo_partido FROM canchas_tipos_partido WHERE id_cancha = :id_cancha";
    $stmtTipoPartido = $conn->prepare($queryTipoPartido);
    $stmtTipoPartido->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtTipoPartido->execute();
    $tipo_partido = $stmtTipoPartido->fetch(PDO::FETCH_ASSOC);
    if (!$tipo_partido) {
        throw new Exception("No se encontró el tipo de partido para la cancha ID: $id_cancha");
    }

    $queryInsertPartido = "INSERT INTO partidos (id_anfitrion, id_tipo_partido, abierto, id_reserva) 
                          VALUES (:id_anfitrion, :id_tipo_partido, 0, :id_reserva)";
    $stmtInsertPartido = $conn->prepare($queryInsertPartido);
    $stmtInsertPartido->bindParam(':id_anfitrion', $id_titular_jugador, PDO::PARAM_INT);
    $stmtInsertPartido->bindParam(':id_tipo_partido', $tipo_partido['id_tipo_partido'], PDO::PARAM_INT);
    $stmtInsertPartido->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmtInsertPartido->execute();
    $id_partido = $conn->lastInsertId();

    $queryInsertParticipanteAnfitrion = "INSERT INTO participantes_partidos (id_partido, id_jugador, id_rol, id_estado, equipo) 
                               VALUES (:id_partido, :id_jugador, 1, 3, 1)";
    $stmtInsertParticipanteAnfitrion = $conn->prepare($queryInsertParticipanteAnfitrion);
    $stmtInsertParticipanteAnfitrion->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
    $stmtInsertParticipanteAnfitrion->bindParam(':id_jugador', $id_titular_jugador, PDO::PARAM_INT);
    $stmtInsertParticipanteAnfitrion->execute();
    $id_participante_anfitrion = $conn->lastInsertId();

    $conn->commit();

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Reserva creada exitosamente',
        'id_reserva' => $id_reserva,
        'datos' => [
            'fecha' => $fecha,
            'fecha_fin' => $fecha_fin,
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'titulo' => $titulo,
            'creador' => [
                'id' => $id_usuario
            ],
            'titular' => [
                'tipo' => $reserva_externa ? 'externo' : 'jugador',
                'id_jugador' => $id_titular_jugador,
                'id_externo' => $id_titular_externo,
                'nombre' => $nombre_completo,
                'telefono' => $telefono
            ],
            'reserva_externa' => $reserva_externa,
            'id_partido' => $id_partido,
            'id_participante_anfitrion' => $id_participante_anfitrion
        ]
    ]);
} catch (PDOException $e) {
    $conn->rollBack();
    error_log("POST_RESERVA - ERROR PDO: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al crear la reserva',
        'detalles' => $e->getMessage()
    ]);
    exit();
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollBack();
    }
    error_log("POST_RESERVA - ERROR GENERAL: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error inesperado al procesar la reserva',
        'detalles' => $e->getMessage()
    ]);
    exit();
}
