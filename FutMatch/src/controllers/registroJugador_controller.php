<?php

/**
 * Registro Jugador Controller - Maneja el registro de nuevos jugadores
 * -------------------------------------------------------------
 * Este archivo procesa las peticiones de registro de nuevos jugadores.
 */

require_once '../../src/app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("[REGISTRO] Inicio del proceso de registro");

    // Sanitizar y validar entradas
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $fechaDeNacimiento = trim($_POST['fechaNacimiento'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $id_sexo = (int)($_POST['genero'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $id_rol = 1; // Rol de jugador (según el schema es 1)
    $id_estado = 1; // Activo

    // Validaciones básicas del servidor
    $errores = [];

    // Validar nombre y apellido (solo letras y espacios)
    if (empty($nombre) || !preg_match('/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/u', $nombre)) {
        $errores[] = 'Nombre inválido';
    }
    if (empty($apellido) || !preg_match('/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/u', $apellido)) {
        $errores[] = 'Apellido inválido';
    }

    // Validar username (alfanumérico)
    if (empty($username) || !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errores[] = 'Nombre de usuario inválido (3-20 caracteres alfanuméricos)';
    }

    // Validar email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Email inválido';
    }

    // Validar fecha de nacimiento (mayor de 18 años)
    if (empty($fechaDeNacimiento)) {
        $errores[] = 'Fecha de nacimiento requerida';
    } else {
        $fecha_nac = new DateTime($fechaDeNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nac)->y;
        if ($edad < 18) {
            $errores[] = 'Debes ser mayor de 18 años';
        }
    }

    // Validar teléfono
    if (empty($telefono) || !preg_match('/^[0-9\-\s]{8,}$/', $telefono)) {
        $errores[] = 'Teléfono inválido';
    }

    // Validar sexo
    if ($id_sexo < 1 || $id_sexo > 3) {
        $errores[] = 'Género inválido';
    }

    // Validar contraseña
    if (empty($password) || strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*\d)/', $password)) {
        $errores[] = 'Contraseña debe tener mínimo 8 caracteres, una minúscula y un número';
    }

    // Si hay errores, regresar al formulario
    if (!empty($errores)) {
        error_log("[REGISTRO] Errores de validación: " . implode(', ', $errores));
        $_SESSION['registration_error'] = implode('. ', $errores);
        header('Location: ' . PAGE_REGISTRO_JUGADOR_PHP);
        exit();
    }

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // PRIMERO, verificar si el email ya está registrado
        $queryCheck = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        error_log("[REGISTRO] Query de verificación: " . $queryCheck);
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->execute(['email' => $email]);
        $emailCount = $stmtCheck->fetchColumn();
        error_log("[REGISTRO] Emails encontrados con " . $email . ": " . $emailCount);
        if ($emailCount > 0) {
            error_log("[REGISTRO] Error: El email ya está registrado");
            $_SESSION['registration_error'] = 'El email ya está registrado. Por favor, usá otro.';
            header('Location: ' . PAGE_REGISTRO_JUGADOR_PHP);
            exit();
        }

        // SEGUNDO, verificar si el username ya está registrado
        $queryCheckUsername = "SELECT COUNT(*) FROM jugadores WHERE username = :username";
        error_log("[REGISTRO] Query de verificación de username: " . $queryCheckUsername);
        $stmtCheckUsername = $conn->prepare($queryCheckUsername);
        $stmtCheckUsername->execute(['username' => $username]);
        $usernameCount = $stmtCheckUsername->fetchColumn();
        error_log("[REGISTRO] Usernames encontrados con " . $username . ": " . $usernameCount);
        if ($usernameCount > 0) {
            error_log("[REGISTRO] Error: El nombre de usuario ya está registrado");
            $_SESSION['registration_error'] = 'El nombre de usuario ya está en uso. Por favor, elegí otro.';
            header('Location: ' . PAGE_REGISTRO_JUGADOR_PHP);
            exit();
        }

        // Insertar nuevo usuario
        $query = "INSERT INTO usuarios (nombre, apellido, email, password, id_estado) 
                  VALUES (:nombre, :apellido, :email, :password, :id_estado)";
        error_log("[REGISTRO] Query: " . $query);
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT), // Hashear password
            'id_estado' => $id_estado
        ]);
        error_log("[REGISTRO] Usuario registrado con éxito: " . $email);
        $id_usuario = $conn->lastInsertId();

        // Insertar relación usuario-rol
        $queryUsuarioRol = "INSERT INTO usuarios_roles (id_usuario, id_rol) VALUES (:id_usuario, :id_rol)";
        error_log("[REGISTRO] Query Usuario-Rol: " . $queryUsuarioRol);
        $stmtUsuarioRol = $conn->prepare($queryUsuarioRol);
        $stmtUsuarioRol->execute([
            'id_usuario' => $id_usuario,
            'id_rol' => $id_rol
        ]);
        error_log("[REGISTRO] Relación usuario-rol creada para usuario ID: " . $id_usuario);

        // Insertar nuevo jugador
        $queryJugador = "INSERT INTO jugadores (id_jugador, username, telefono, fecha_nacimiento, id_sexo)     
                         VALUES (:id_usuario, :username, :telefono, :fecha_nacimiento, :id_sexo)";
        error_log("[REGISTRO] Query Jugador: " . $queryJugador);
        $stmtJugador = $conn->prepare($queryJugador);
        $stmtJugador->execute([
            'id_usuario' => $id_usuario,
            'username' => $username,
            'fecha_nacimiento' => $fechaDeNacimiento,
            'telefono' => $telefono,
            'id_sexo' => $id_sexo
        ]);
        error_log("[REGISTRO] Datos de jugador insertados con éxito para el usuario ID: " . $id_usuario);

        // Confirmar transacción
        $conn->commit();

        // Auto-login: Establecer sesión del usuario recién registrado
        $_SESSION['user_id'] = $id_usuario;
        $_SESSION['email'] = $email;
        $_SESSION['user_type'] = 'jugador'; // Es un jugador recién registrado
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;

        error_log("[REGISTRO] Auto-login establecido para usuario ID: " . $id_usuario);

        // Redirigir al inicio del jugador con mensaje de bienvenida
        $_SESSION['registration_success'] = '¡Bienvenido a FutMatch, ' . $nombre . '!';
        header('Location: ' . PAGE_INICIO_JUGADOR);
        exit();
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $conn->rollBack();
        error_log("[REGISTRO] Error en el registro: " . $e->getMessage());
        $_SESSION['registration_error'] = 'Error al registrar el usuario. Por favor, intentá nuevamente.';
        header('Location: ' . PAGE_REGISTRO_JUGADOR_PHP);
        exit();
    }
} else {
    // Si no es POST, redirigir al formulario de registro
    header('Location: ' . PAGE_REGISTRO_JUGADOR_PHP);
    exit();
}
