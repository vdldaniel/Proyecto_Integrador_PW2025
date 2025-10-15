# Sistema de Autenticación - FutMatch

## Resumen del Sistema

Este documento explica el sistema de autenticación implementado que permite:
- Login desde modal (UX fluida sin salir de la página)
- Redirección automática a la página original después del login
- Interceptación de acciones que requieren autenticación
- Soporte para "Recordarme"
- Login desde página completa (fallback)

---

## Arquitectura

```
Usuario Guest → Intenta acción protegida
    ↓
Modal de Login (rápido, sin salir de página)
    ↓
POST a /src/controllers/auth/login.php
    ↓
Validación y creación de sesión
    ↓
Redirect a URL original + sesión activa
    ↓
Usuario puede completar la acción
```

---

## Archivos Creados/Modificados

### 1. **navbarGuest.php** (Modificado)
**Ubicación:** `src/app/navbarGuest.php`

**Cambios:**
- Eliminado: Botones de "Mi Perfil", "Notificaciones", "Configuración", "Cerrar Sesión"
- Añadido: Botón "Iniciar Sesión" (abre modal)
- Añadido: Botón "Registrarse" (link a registro)
- Añadido: Modal de login completo
- Añadido: Script para capturar URL actual

**Uso:**
```php
<?php
// En inicioJugador.php o cualquier página
$is_logged_in = isset($_SESSION['user_id']);

if ($is_logged_in) {
    require_once NAVBAR_JUGADOR_COMPONENT;
} else {
    require_once NAVBAR_GUEST_COMPONENT;
}
?>
```

---

### 2. **login.php** (Nuevo controlador)
**Ubicación:** `src/controllers/auth/login.php`

**Funcionalidad:**
- Recibe POST con email, password, remember, redirect_url
- Valida credenciales (TODO: conectar con BD)
- Crea sesión con datos del usuario
- Redirige a la URL original o página de inicio

**Credenciales de prueba (TEMPORAL):**
```
Jugador:
- Email: jugador@test.com
- Password: 12345678

Admin Cancha:
- Email: admin@test.com
- Password: 12345678
```

**Flujo completo con BD:**
```php
1. Validar input
2. Buscar usuario: SELECT * FROM usuarios WHERE email = ?
3. Verificar password: password_verify($input, $hash)
4. Verificar cuenta activa
5. Crear sesión
6. Si "recordarme": generar token y guardar
7. Registrar login en logs
8. Redirigir según tipo de usuario
```

---

### 3. **auth-required.js** (Nuevo script)
**Ubicación:** `src/scripts/auth-required.js`

**Funcionalidad:**
- Detecta si usuario está logueado
- Intercepta clicks en elementos con `data-requires-auth="true"`
- Muestra modal de login cuando guest intenta acción protegida
- Guarda formularios pendientes en sessionStorage
- Restaura formularios después del login

**Uso en HTML:**
```html
<!-- Botón que requiere autenticación -->
<button data-requires-auth="true" 
        data-action="unirte al partido"
        data-target-url="/partidos/unirse/123"
        class="btn btn-primary">
    Unirse al partido
</button>

<!-- Formulario que requiere autenticación -->
<form data-requires-auth="true" 
      action="/equipos/crear" 
      method="POST">
    <input type="text" name="nombre" required>
    <button type="submit">Crear equipo</button>
</form>
```

**Uso en JavaScript:**
```javascript
// Validar autenticación manualmente
document.getElementById('btnCrearEquipo').addEventListener('click', function() {
    if (!requireAuth('Inicia sesión para crear un equipo')) {
        return; // Muestra modal y detiene ejecución
    }
    // Continuar con la acción (usuario está logueado)
    crearEquipo();
});
```

---

### 4. **config.php** (Modificado)
**Ubicación:** `src/app/config.php`

**Cambios:**
```php
// Componentes
define("NAVBAR_GUEST_COMPONENT", __DIR__ . "/navbarGuest.php");

// Scripts
define("JS_AUTH_REQUIRED", SRC_PATH . "scripts/auth-required.js");
```

---

## Flujo de Redirección

### Escenario 1: Usuario guest hace click en botón protegido

```
1. Usuario en: /canchas/detalle/5
2. Click en "Reservar" (data-requires-auth="true")
3. JavaScript intercepta, muestra modal
4. Usuario completa login
5. POST a /controllers/auth/login.php con:
   - email
   - password
   - redirect_url = /canchas/detalle/5
6. Login exitoso → header('Location: /canchas/detalle/5')
7. Usuario vuelve a /canchas/detalle/5 CON SESIÓN
8. Puede hacer click en "Reservar" (ya está logueado)
```

### Escenario 2: Usuario guest envía formulario protegido

```
1. Usuario llena formulario de crear equipo
2. Submit (form data-requires-auth="true")
3. JavaScript intercepta:
   - Guarda form data en sessionStorage
   - Muestra modal de login
4. Usuario completa login
5. Redirect a página actual
6. JavaScript detecta sessionStorage con form pendiente
7. Crea form temporal y lo envía automáticamente
8. Equipo se crea exitosamente
```

---

## Estructura del Modal

```html
<div class="modal fade" id="modalLogin">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Iniciar Sesión</h5>
            </div>
            <div class="modal-body">
                <form action="/controllers/auth/login.php" method="POST">
                    <input type="hidden" name="redirect_url" value="">
                    <input type="email" name="email" required>
                    <input type="password" name="password" required>
                    <input type="checkbox" name="remember">
                    <button type="submit">Iniciar Sesión</button>
                    
                    <!-- Links adicionales -->
                    <a href="/auth/forgot.php">¿Olvidaste tu contraseña?</a>
                    <a href="/auth/registroJugador.php">Registrarse</a>
                    <a href="/auth/registroAdminCancha.php">Registrá tu cancha</a>
                </form>
            </div>
        </div>
    </div>
</div>
```

---

## Sesiones y Variables

### Variables de sesión después del login exitoso:

```php
$_SESSION['user_id']       // ID del usuario (1, 2, 3...)
$_SESSION['user_name']     // Nombre completo ("Juan Pérez")
$_SESSION['user_email']    // Email
$_SESSION['user_type']     // 'jugador' | 'admin_cancha' | 'admin_sistema'
$_SESSION['logged_in']     // true
$_SESSION['cancha_id']     // (solo admin_cancha) ID de la cancha
```

### Cookies (si marca "Recordarme"):

```php
setcookie('remember_token', $token, time() + (86400 * 30), '/');
// Token único guardado en BD para auto-login
```

---

## Checklist de Implementación

### Fase 1: Frontend (COMPLETADA)
- [x] navbarGuest.php con modal
- [x] Script auth-required.js
- [x] Atributos data-requires-auth en elementos protegidos
- [x] Constantes en config.php

### Fase 2: Backend (EN PROGRESO)
- [x] Controlador login.php básico
- [ ] Conectar con base de datos
- [ ] Hash de passwords (password_hash/verify)
- [ ] Validación de cuenta activa
- [ ] Tabla de tokens para "recordarme"
- [ ] Logs de login

### Fase 3: Middleware (PENDIENTE)
- [ ] Crear auth.php middleware
- [ ] Función requireAuth() para proteger páginas
- [ ] Auto-login desde cookie remember_token
- [ ] CSRF protection

### Fase 4: Logout
- [ ] Controlador logout.php
- [ ] Destruir sesión
- [ ] Eliminar cookie remember
- [ ] Redirect a landing

---

## Seguridad

### Medidas implementadas:
Input sanitization (filter_input)
Prepared statements (en TODO)
Session regeneration
Redirect URL validation

### Medidas por implementar:
- [ ] CSRF tokens en formularios
- [ ] Rate limiting (intentos de login)
- [ ] Password hashing con bcrypt
- [ ] HTTPS only cookies
- [ ] Session timeout
- [ ] IP validation
- [ ] 2FA opcional

---

## Ejemplo Completo: Página con Autenticación Condicional

### inicioJugador.php (Página pública con acciones protegidas)

```php
<?php
session_start();
require_once '../../../src/app/config.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['user_name'] : 'Invitado';

$current_page = 'inicioJugador';
$page_title = 'Inicio - FutMatch';
$page_css = [CSS_PAGES_INICIO_JUGADOR];

require_once HEAD_COMPONENT;
?>
<!-- Atributo importante para JavaScript -->
<body data-logged-in="<?= $is_logged_in ? 'true' : 'false' ?>">
  <?php 
  // Navbar condicional
  if ($is_logged_in) {
      require_once NAVBAR_JUGADOR_COMPONENT;
  } else {
      require_once NAVBAR_GUEST_COMPONENT;
  }
  ?>
  
  <main class="container">
    <!-- Saludo -->
    <h2>¡Hola, <?= htmlspecialchars($user_name) ?>!</h2>
    
    <!-- Card pública: Todos pueden ver -->
    <div class="card">
      <h5>Explorar Canchas</h5>
      <a href="<?= PAGE_CANCHAS_LISTADO ?>" class="btn btn-success">
        Ver canchas
      </a>
    </div>
    
    <!-- Card protegida: Solo usuarios logueados -->
    <div class="card">
      <h5>Reservar Cancha</h5>
      <?php if ($is_logged_in): ?>
        <a href="<?= PAGE_CANCHA_RESERVAR ?>" class="btn btn-primary">
          Reservar ahora
        </a>
      <?php else: ?>
        <!-- Botón que abre modal de login -->
        <button data-requires-auth="true" 
                data-action="reservar una cancha"
                data-target-url="<?= PAGE_CANCHA_RESERVAR ?>"
                class="btn btn-outline-primary">
          Reservar
        </button>
        <small class="text-muted">
          <a href="<?= PAGE_LANDING_PHP ?>">Inicia sesión</a> para reservar
        </small>
      <?php endif; ?>
    </div>
    
    <!-- Card solo para logueados -->
    <?php if ($is_logged_in): ?>
      <div class="card">
        <h5>Mis Partidos</h5>
        <a href="<?= PAGE_PARTIDOS_JUGADOR ?>" class="btn btn-info">
          Ver mis partidos
        </a>
      </div>
    <?php endif; ?>
  </main>
  
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_AUTH_REQUIRED ?>"></script>
</body>
```

---

## Próximos Pasos

1. **Conectar con Base de Datos**
   - Crear tabla `usuarios` si no existe
   - Implementar queries en login.php
   - Hash passwords con `password_hash()`

2. **Implementar Middleware**
   - Crear `auth.php` con función `requireAuth()`
   - Proteger páginas privadas (perfil, equipos, etc.)

3. **Logout**
   - Crear controlador `logout.php`
   - Botón en navbarJugador.php

4. **Remember Me**
   - Tabla `remember_tokens` en BD
   - Auto-login check en head.php

5. **CSRF Protection**
   - Generar tokens en formularios
   - Validar en controladores

---

## Referencias

- Bootstrap 5 Modals: https://getbootstrap.com/docs/5.3/components/modal/
- PHP Sessions: https://www.php.net/manual/en/book.session.php
- Password Hashing: https://www.php.net/manual/en/function.password-hash.php
- sessionStorage: https://developer.mozilla.org/en-US/docs/Web/API/Window/sessionStorage

---

**Última actualización:** Octubre 15, 2025  
**Autor:** Equipo FutMatch
