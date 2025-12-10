<?php

/**
 * Registro Admin Cancha Controller - Maneja el registro de nuevos administradores de cancha
 * -----------------------------------------------------------------------------------------
 * Este archivo procesa las peticiones de registro de nuevos administradores de cancha.
 * Guarda la solicitud con la ubicación de la cancha (coordenadas + dirección).
 */

require_once '../../src/app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("[REGISTRO_ADMIN_CANCHA] Inicio del proceso de registro");

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $nombreCancha = trim($_POST['nombreCancha'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    // Datos de ubicación (del mapa)
    $direccion = trim($_POST['direccion'] ?? '');
    $latitud = trim($_POST['latitud'] ?? '');
    $longitud = trim($_POST['longitud'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');

    // Preferencias de contacto
    $contacto = trim($_POST['contacto'] ?? '');
    $horario = trim($_POST['horario'] ?? '');

    error_log("[REGISTRO_ADMIN_CANCHA] Datos recibidos - Email: $email, Cancha: $nombreCancha");
    error_log("[REGISTRO_ADMIN_CANCHA] Ubicación - Lat: $latitud, Lng: $longitud, Dir: $direccion");

    try {
        // PRIMERO: Verificar si el email ya está registrado
        $queryCheck = "SELECT COUNT(*) FROM solicitudes_admin_cancha WHERE email = :email";
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->execute(['email' => $email]);
        $emailCount = $stmtCheck->fetchColumn();

        if ($emailCount > 0) {
            error_log("[REGISTRO_ADMIN_CANCHA] Error: El email ya tiene una solicitud registrada");
            $_SESSION['registration_error'] = 'Ya existe una solicitud con este email. Por favor, contactanos para más información.';
            header('Location: ' . PAGE_REGISTRO_ADMIN_CANCHA_PHP);
            exit();
        }

        // SEGUNDO: Insertar dirección en la tabla direcciones
        $queryDireccion = "INSERT INTO direcciones (direccion_completa, latitud, longitud, pais, provincia, localidad) 
                          VALUES (:direccion, :latitud, :longitud, :pais, :provincia, :localidad)";
        error_log("[REGISTRO_ADMIN_CANCHA] Query dirección: " . $queryDireccion);
        $stmtDireccion = $conn->prepare($queryDireccion);
        $stmtDireccion->execute([
            'direccion' => $direccion,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'pais' => $pais,
            'provincia' => $provincia,
            'localidad' => $localidad
        ]);
        $id_direccion = $conn->lastInsertId();
        error_log("[REGISTRO_ADMIN_CANCHA] Dirección insertada con ID: " . $id_direccion);

        // TERCERO: Insertar solicitud de admin cancha
        // Nota: id_verificador será asignado por el admin del sistema cuando revise la solicitud
        // Por ahora usamos 1 (primer admin del sistema) como placeholder
        $querySolicitud = "INSERT INTO solicitudes_admin_cancha 
                          (nombre, apellido, email, telefono, nombre_cancha, id_direccion, id_verificador, observaciones) 
                          VALUES (:nombre, :apellido, :email, :telefono, :nombre_cancha, :id_direccion, 1, :observaciones)";
        error_log("[REGISTRO_ADMIN_CANCHA] Query solicitud: " . $querySolicitud);

        $observaciones = "Contactar por $contacto en horario de $horario";

        $stmtSolicitud = $conn->prepare($querySolicitud);
        $stmtSolicitud->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => $telefono,
            'nombre_cancha' => $nombreCancha,
            'id_direccion' => $id_direccion,
            'observaciones' => $observaciones
        ]);

        error_log("[REGISTRO_ADMIN_CANCHA] Solicitud registrada con éxito para: " . $email);

        // Redirigir con mensaje de éxito
        $_SESSION['registration_success'] = 'Solicitud enviada con éxito. Serás contactado a la brevedad por nuestro equipo.';
        header('Location: ' . PAGE_LANDING_PHP);
        exit();
    } catch (PDOException $e) {
        error_log("[REGISTRO_ADMIN_CANCHA] Error en el registro: " . $e->getMessage());
        $_SESSION['registration_error'] = 'Error al procesar la solicitud. Por favor, intentá nuevamente.';
        header('Location: ' . PAGE_REGISTRO_ADMIN_CANCHA_PHP);
        exit();
    }
} else {
    // Si no es POST, redirigir al formulario
    header('Location: ' . PAGE_REGISTRO_ADMIN_CANCHA_PHP);
    exit();
}
