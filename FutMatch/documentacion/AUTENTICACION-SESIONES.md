# Sistema de Autenticación y Sesiones - FutMatch

## Documentación Técnica

Este documento explica cómo funciona el sistema de autenticación y sesiones implementado en FutMatch.

---

## Arquitectura

### Componentes Principales

1. **`config.php`** - Configuración global

   - Conexión a base de datos centralizada
   - Constantes de rutas y nombres de tablas
   - Credenciales de BD como constantes

2. **`auth-required.php`** - Sistema de autenticación

   - Manejo de sesiones
   - Funciones de verificación de usuario
   - Control de acceso por tipo de usuario

3. **`modalLogin.php`** - Modal de login reutilizable

   - Componente visual para login
   - Puede incluirse en cualquier página

4. **`login_controller.php`** - Controlador de login

   - Procesa credenciales
   - Establece sesiones
   - Redirige según tipo de usuario

5. **`logout.php`** - Controlador de cierre de sesión
   - Destruye sesiones
   - Limpia cookies
   - Redirige al landing

---

## 🚀 Uso en Páginas

### 1. Página que NO requiere autenticación (Invitado)

```php
<?php
require_once("../../../src/app/config.php");

// Definir página actual
$current_page = 'nombrePagina';

// Título y CSS
$page_title = 'Título - FutMatch';
$page_css = [CSS_PAGES_EJEMPLO];

// Cargar head
require_once HEAD_COMPONENT;
?>
<body>
  <?php require_once NAVBAR_GUEST_COMPONENT; ?>

  <!-- Contenido -->

  <?php
  // Incluir modal de login al final
  require_once MODAL_LOGIN_COMPONENT;
  ?>
</body>
```

### 2. Página que REQUIERE autenticación

````php
<?php
require_once("../../../src/app/config.php");

// ... //

$page_css = [CSS_PAGES_EJEMPLO];

// IMPORTANTE: Requiere autenticación
require_once AUTH_REQUIRED_COMPONENT;
requireAuth(); // Redirige al login si no está autenticado

// ... //

?> ```

### 3. Página que requiere un tipo de usuario específico

```php
<?php
require_once("../../../src/app/config.php");

$current_page = 'nombrePagina';
$page_title = 'Título - FutMatch';
$page_css = [CSS_PAGES_EJEMPLO];

// Autenticación y verificación de tipo
require_once AUTH_REQUIRED_COMPONENT;
requireAuth();
requireUserType('jugador'); // Solo jugadores pueden acceder

// Obtener info del usuario
$currentUser = getCurrentUser();

require_once HEAD_COMPONENT;
?>
<body>
  <?php require_once NAVBAR_JUGADOR_COMPONENT; ?>

  <h1>Bienvenido <?= htmlspecialchars($currentUser['nombre']) ?></h1>

</body>
````

---

## Funciones Disponibles (auth-required.php)

### `isLoggedIn()`

```php
if (isLoggedIn()) {
    echo "Usuario autenticado";
}
```

### `requireAuth()`

```php
requireAuth(); // Redirige al login si no está autenticado
```

### `isUserType($type)`

```php
if (isUserType('admin_cancha')) {
    echo "Es administrador de cancha";
}
```

### `requireUserType($type)`

```php
requireUserType('jugador'); // Solo permite jugadores
```

### `getCurrentUser()`

```php
$user = getCurrentUser();
echo $user['nombre']; // Nombre del usuario
echo $user['email'];  // Email del usuario
echo $user['user_type']; // Tipo: jugador, admin_cancha, admin_sistema
```

---

## Estructura de Base de Datos (lo relevante para SESIONES)

### Tabla: `usuarios`

```sql
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_rol INT NOT NULL,
    id_estado INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES roles_usuarios(id_rol),
    FOREIGN KEY (id_estado) REFERENCES estados_usuarios(id_estado)
);
```

### Tabla: `roles_usuarios`

```sql
CREATE TABLE roles_usuarios (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Valores:
-- 1 = 'admin_sistema'
-- 2 = 'admin_cancha'
-- 3 = 'jugador'
```

### Tabla: `estados_usuarios`

```sql
CREATE TABLE estados_usuarios (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Valores:
-- 1 = 'Activo'
-- 2 = 'Inactivo'
-- 3 = 'Suspendido'
```

### Constantes de Tablas (config.php)

```php
TABLE_USUARIOS
TABLE_ROLES_USUARIOS
TABLE_ESTADOS_USUARIOS
```

(Revisar config.php para ver todos)

Usar en consultas:

```php
$stmt = $conn->prepare("SELECT * FROM " . TABLE_PARTIDOS . " WHERE fecha >= ?");
```

### Nota Importante sobre el Login

El sistema mapea los roles de la BD a tipos de usuario en la sesión:

- `id_rol = 1` → `$_SESSION['user_type'] = 'admin_sistema'`
- `id_rol = 2` → `$_SESSION['user_type'] = 'admin_cancha'`
- `id_rol = 3` → `$_SESSION['user_type'] = 'jugador'`

El estado del usuario debe ser `id_estado = 1` (Activo) para poder iniciar sesión.

---

## Seguridad Implementada

### 1. Contraseñas

- **Almacenamiento**: Texto plano (proyecto académico)
- **Verificación**: Comparación directa

```php
// Al login
if ($password === $usuario['password']) {
    // Login exitoso
}
```

### 2. Consultas Preparadas (PDO)

```php
// Utilizar prepare:
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
$stmt->execute(['email' => $email]);

// Vulnerable a SQL Injection:
$query = "SELECT * FROM usuarios WHERE email = '$email'";
```

### 3. Sanitización de Salida

```php
// Siempre usar htmlspecialchars() al mostrar datos
echo htmlspecialchars($user['nombre']);
```

## Ejemplos Prácticos

### Verificar si está logueado antes de mostrar contenido

```php
<?php if (isLoggedIn()): ?>
    <a href="<?= PAGE_MIS_PARTIDOS_JUGADOR ?>">Mis Partidos</a>
<?php else: ?>
    <button data-bs-toggle="modal" data-bs-target="#modalLogin">
        Iniciar sesión para ver tus partidos
    </button>
<?php endif; ?>
```

### Mostrar nombre del usuario

```php
<?php
$user = getCurrentUser();
if ($user):
?>
    <p>Hola, <?= htmlspecialchars($user['nombre']) ?>!</p>
<?php endif; ?>
```

### Consulta a base de datos con tabla constante

```php
try {
    $stmt = $conn->prepare("
        SELECT * FROM " . TABLE_PARTIDOS . "
        WHERE fecha >= CURDATE()
        AND genero = :genero
    ");
    $stmt->execute(['genero' => $genero]);
    $partidos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error en consulta: " . $e->getMessage());
    echo "Error al cargar los partidos";
}
```

---

## Buenas Prácticas

### HACER:

1. Usar `requireAuth()` en todas las páginas privadas
2. Usar consultas preparadas SIEMPRE
3. Sanitizar con `htmlspecialchars()` al mostrar datos
4. Usar constantes de tablas y rutas de config.php
5. Manejar errores con try-catch
6. Validar datos tanto en cliente como servidor

### NO HACER:

1. Concatenar variables en SQL directamente
2. Guardar contraseñas en texto plano
3. Confiar solo en validación JavaScript
4. Hardcodear nombres de tablas o rutas
5. Mostrar errores de BD al usuario (usar error_log)
6. Olvidar verificar tipos de usuario
