# UN POCO SOBRE CÓMO FUNCIONA EL SISTEMA

-- Este documento explica cómo funciona el sistema de autenticación y sesiones implementado en FutMatch.

## Arquitectura

### Componentes Principales

1. **`config.php`** - Configuración global

   - Conexión a base de datos centralizada (reemplaza connection.php)
   - Constantes de rutas y nombres de tablas (centraliza rutas)

2. **`modalLogin.php`** - Modal de login reutilizable

   - Componente visual para login
   - Puede incluirse en cualquier página

3. **`login_controller.php`** - Controlador de login

   - Procesa credenciales
   - Establece sesiones
   - Redirige según tipo de usuario

4. **`logout.php`** - Controlador de cierre de sesión
   - Destruye sesiones
   - Limpia cookies
   - Redirige al landing
