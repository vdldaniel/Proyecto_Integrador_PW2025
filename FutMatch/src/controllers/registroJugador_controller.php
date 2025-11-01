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
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $fechaDeNacimiento = trim($_POST['fechaNacimiento'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $id_sexo = trim($_POST['genero'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $id_rol = 3; // Rol de jugador
    $id_estado = 1; // Activo

    try {
        // PRIMERO, verificar si el email ya está registrado
        $queryCheck = "SELECT COUNT(*) FROM " . TABLE_USUARIOS . " WHERE email = :email";
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
        $queryCheckUsername = "SELECT COUNT(*) FROM " . TABLE_JUGADORES . " WHERE username = :username";
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
        $query = "INSERT INTO " . TABLE_USUARIOS . " (nombre, apellido, email, password, id_rol, id_estado) 
                  VALUES (:nombre, :apellido, :email, :password, :id_rol, :id_estado)";
        error_log("[REGISTRO] Query: " . $query);
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => $password,
            'id_rol' => $id_rol,
            'id_estado' => $id_estado
        ]);
        error_log("[REGISTRO] Usuario registrado con éxito: " . $email);
        $id_usuario = $conn->lastInsertId();

        // Insertar nuevo ESPACIO (perfil del jugador)
        $id_tipo_espacio = 2; // Tipo de espacio para jugador
        $queryEspacio = "INSERT INTO " . TABLE_ESPACIOS . " (id_tipo_espacio) VALUES (:id_tipo_espacio)";
        error_log("[REGISTRO] Query Espacio: " . $queryEspacio);
        $stmtEspacio = $conn->prepare($queryEspacio);
        $stmtEspacio->execute(['id_tipo_espacio' => $id_tipo_espacio]);
        $id_espacio = $conn->lastInsertId();
        error_log("[REGISTRO] Espacio creado con ID: " . $id_espacio);

        // Relacionar nuevo ESPACIO con USUARIO
        $queryUsuarioEspacio = "INSERT INTO " . TABLE_USUARIOS_ESPACIOS . " (id_espacio, id_usuario) 
                                 VALUES (:id_espacio, :id_usuario)";
        error_log("[REGISTRO] Query Usuario-Espacio: " . $queryUsuarioEspacio);
        $stmtUsuarioEspacio = $conn->prepare($queryUsuarioEspacio);
        $stmtUsuarioEspacio->execute([
            'id_espacio' => $id_espacio,
            'id_usuario' => $id_usuario
        ]);
        error_log("[REGISTRO] Relación usuario-espacio creada para usuario ID: " . $id_usuario);

        // + Relacionar USUARIO con ESPACIO global (ID 1)
        $queryUsuarioEspacioGlobal = "INSERT INTO " . TABLE_USUARIOS_ESPACIOS . " (id_espacio, id_usuario) 
                                       VALUES (1, :id_usuario)";
        error_log("[REGISTRO] Query Usuario-Espacio Global: " . $queryUsuarioEspacioGlobal);
        $stmtUsuarioEspacioGlobal = $conn->prepare($queryUsuarioEspacioGlobal);
        $stmtUsuarioEspacioGlobal->execute([
            'id_usuario' => $id_usuario
        ]);
        error_log("[REGISTRO] Relación usuario-espacio global creada para usuario ID: " . $id_usuario);

        // Insertar nuevo JUGADOR
        $queryJugador = "INSERT INTO " . TABLE_JUGADORES . " (id_jugador, username, telefono, fecha_nacimiento, id_sexo, id_espacio)     
                         VALUES (:id_usuario, :username, :telefono, :fecha_nacimiento, :id_sexo, :id_espacio)";
        error_log("[REGISTRO] Query Jugador: " . $queryJugador);
        $stmtJugador = $conn->prepare($queryJugador);
        $stmtJugador->execute([
            'id_usuario' => $id_usuario,
            'username' => $username,
            'fecha_nacimiento' => $fechaDeNacimiento,
            'telefono' => $telefono,
            'id_sexo' => $id_sexo,
            'id_espacio' => $id_espacio
        ]);
        error_log("[REGISTRO] Datos de jugador insertados con éxito para el usuario ID: " . $id_usuario);

        // Redirigir al landing page con mensaje de éxito
        $_SESSION['registration_success'] = 'Registro exitoso. Ahora podés iniciar sesión.';
        header('Location: ' . PAGE_INICIO_JUGADOR);
        exit();
    } catch (PDOException $e) {
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
?>