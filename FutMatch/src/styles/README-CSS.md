# Documentación de Arquitectura CSS - FutMatch

## Estructura General

La arquitectura CSS del proyecto sigue el patrón **ITCSS (Inverted Triangle CSS)** modificado:

```
base.css          → Variables, reset, tipografía global
layout.css        → Estructura de página (navbar, sidebar, hero, grid)
components.css    → Componentes reutilizables (botones, cards, badges, forms)
pages/            → Estilos específicos de cada página
  ├── landing.css
  ├── inicioJugador.css
  ├── agenda.css
  └── dashboardAdminCancha.css
[específicos]     → Archivos legacy para páginas individuales
```

---

## Archivos Principales

### 1. **base.css** - Fundamentos del proyecto
**Propósito:** Variables CSS globales, reset, tipografía, utilidades

**Contenido:**
- **Variables CSS globales**:
  - Radios y espaciados (`--radius`, `--card-radius`)
  - Colores de tema (modo claro/oscuro)
  - Hero overlays (`--hero-overlay-bg`)
  - Tarjetas (backgrounds, borders, headers)
  - Enlaces y badges
  - Estados de partidos (confirmed, waiting, pending, searching)

- **Reset y configuración base**:
  - `box-sizing: border-box` global
  - Body con flexbox vertical
  - Overflow-x hidden

- **Tipografía global**:
  - Font-family: Montserrat
  - Pesos: fw-800, fw-600
  - Headings con font-weight 600
  - Brand text con sombras

- **Scrollbar personalizado**:
  - Width: 6px
  - Colores adaptativos al tema

- **Utilidades**:
  - `.icon-large` (font-size: 3rem)
  - Media query para `prefers-reduced-motion`

**Cuándo usar:** Siempre incluir primero. Define el sistema de diseño.

---

### 2. **layout.css** - Estructura de página
**Propósito:** Layout principal, navbar, sidebar, containers, grid

**Contenido:**
- **Containers y layouts principales**:
  - `.main-container` - Contenedor principal flex
  - `.main-content` - Contenido con min-height y padding
  - `.hero` - Hero section con overlay y background fixed
  - `.bg-image` - Background cover para headers

- **Navbar**:
  - Logo (45px width)
  - **Dropdown fixes** (IMPORTANTE):
    - `.navbar .dropdown` → `position: relative` (para que aparezca debajo del botón)
    - `.navbar .dropdown-menu` → `left: 0` (alineado a izquierda)
    - `.navbar .dropdown.dropdown-perfil .dropdown-menu` → `right: 0` (perfil a la derecha)
  - Botones activos con color primario

- **Sidebar**:
  - `.sidebar-fijo` - 280px width, overflow-y auto
  - Scrollbar personalizado
  - Dark mode adaptativo
  - `.content-with-sidebar` - Margin left en desktop

- **Offcanvas (Mobile sidebar)**:
  - Flex column layout
  - Hover effects
  - Botones con transparencia
  - Títulos de sección con mayúsculas

- **Grid y contenedores especiales**:
  - `.listado-grid` - Para grids de tarjetas
  - `#section-content` - Contenido con glassmorphism
  - Vistas de calendario (mensual/semanal)

- **Responsive**:
  - Mobile: padding reducido, botones más pequeños
  - Tablet: form-control min-width

**Cuándo usar:** En todas las páginas con navbar/sidebar.

---

### 3. **components.css** - Componentes reutilizables
**Propósito:** Botones, cards, badges, forms, búsqueda, links especiales

**Contenido:**
- **Botones**:
  - Transitions globales
  - Focus states (box-shadow)
  - `.btn-team-primary` / `.btn-team-secondary` - Para equipos
  - Botones sidebar con hover

- **Cards**:
  - Background con glassmorphism (`backdrop-filter: blur(10px)`)
  - Hover: `translateY(-2px)` y box-shadow
  - `.card-action` - Cards especiales de landing (hover más pronunciado)
  - `.option-card` - Cards con scale en hover
  - `.card-img-top` / `.card-img` - Imágenes con object-fit

- **Badges**:
  - `.badge-host` - Verde para anfitrión
  - `.badge-guest` - Azul para invitado  
  - `.badge-applicant` - Naranja para solicitante
  - `.status-confirmed` / `.status-waiting` / `.status-pending` / `.status-searching`
  - Hover: `scale(1.05)`

- **Forms**:
  - Border-radius 15px
  - Labels con font-weight 600
  - Dark mode: backgrounds adaptados, focus states
  - Inputs en tablas: width 100%

- **Búsqueda**:
  - `.busqueda-container` - Max-width 1200px centrado
  - `.busqueda-wrapper` - Card con bordes redondeados
  - `.barra-de-busqueda` - Flex con input y icono
  - `.input-busqueda` - Border-radius 30px (pill shape)
  - `.busqueda-icon` - Icono posicionado absolutamente

- **List Groups**:
  - Hover con background azul translúcido
  - Text-truncate con max-width

- **Links especiales**:
  - `.link-cancha` - Link con borde punteado, margin-bottom 2rem
  - `.chevron` - Rotación 180deg cuando expanded

- **Estado vacío**:
  - `.empty-state` - Display grande con opacidad, padding 3rem
  - Icono, título y texto centrados

- **Responsive**:
  - Mobile: badges y botones más pequeños
  - `prefers-reduced-motion`: Sin transiciones ni transforms

**Cuándo usar:** Para cualquier componente estándar (botones, cards, forms).

---

## Archivos de Páginas Específicas

### 4. **pages/landing.css** - Página de autenticación
**Propósito:** Login, registro, recuperar contraseña

**Contenido:**
- **Variables adicionales**:
  - `--card-text-color`, `--card-muted-color`

- **Tipografía**:
  - `.brand-title` / `.brand-tagline` - Text-shadow pronunciado
  - `.fw-800` / `.fw-600` - Font weights

- **Chevron animado**:
  - Rotación en collapse expanded

- **Botones en cards**:
  - Mejoras para `btn-success` y `btn-outline-secondary`

- **Formularios glassmorphism (Dark mode)**:
  - Inputs: `rgba(33, 37, 41, 0.5)` con `backdrop-filter: blur(10px)`
  - Hover: opacity 0.6
  - Focus: opacity 0.7, border primary
  - Estados de validación: backgrounds con opacity 15%
  - Checkboxes: transparentes con blur
  - Select options: `rgba(33, 37, 41, 0.95)` para dropdown

- **Divisores de sección**:
  - `.form-divider` - Línea sutil `rgba(255, 255, 255, 0.15)`, opacity 0.6

**Cuándo usar:** En landing.php, registroJugador.php, registroAdminCancha.php, forgot.php

---

### 5. **pages/inicioJugador.css** - Dashboard del jugador
**Propósito:** Página de inicio con tarjetas de acciones

**Contenido:**
- Main content centrado vertical y horizontalmente
- Grid de tarjetas con gap
- Icons grandes (3rem)
- Cards con shadow-lg

**Cuándo usar:** En inicioJugador.php

---

### 6. **pages/agenda.css** - Calendario de admin cancha
**Propósito:** Vista de calendario semanal/mensual

**Contenido:**
- Vistas mensual y semanal
- Celdas de calendario
- Eventos/reservas
- Table layout fixed

**Cuándo usar:** En agenda.html del admin cancha

---

### 7. **pages/dashboardAdminCancha.css** - Dashboard admin
**Propósito:** Panel de administración de canchas

**Contenido:**
- Estadísticas
- Cards de resumen
- Acciones rápidas

**Cuándo usar:** En inicioAdminCancha.php

---

### 8. **canchas-listado.css** - Listado de canchas
**Propósito:** Búsqueda y filtrado de canchas

**Contenido:**
- `.busqueda-container` - Contenedor centrado max-width 1200px
- `.busqueda-wrapper` - Card principal con bordes redondeados
- `.busqueda-header` - Encabezado con padding y separador
- `.barra-de-busqueda` - Flex para input y icono
- `.input-busqueda` - Input pill con padding para icono
- `.busqueda-icon` - Icono lupa posicionado absolutamente a la derecha
- `.filtro-de-busquedas` - Área de filtros con fondo diferenciado
- `.listado-grid` - Grid donde se muestran las tarjetas
- `.card-img` - Imágenes 200px height con object-fit cover

**Cuándo usar:** En canchas-listado.html

---

### 9. **partidos-jugador.css** - Mis partidos
**Propósito:** Lista de partidos del jugador con estados y roles

**Contenido:**
- **Variables de colores**:
  - Badges de roles (host, guest, applicant)
  - Estados de partido (confirmed, waiting, pending, searching)
  - Colores de tarjetas (light/dark mode)

- **Badges de roles**:
  - `.badge-host` - Verde (#198754)
  - `.badge-guest` - Azul (#0d6efd)
  - `.badge-applicant` - Naranja (#fd7e14)

- **Estados de partido**:
  - `.status-confirmed` - Verde
  - `.status-waiting` - Amarillo
  - `.status-pending` - Gris
  - `.status-searching` - Morado

- **Tarjetas**:
  - Backgrounds sutiles con blur
  - Hover: translateY(-2px)

- **Botones de equipo**:
  - `.btn-team-primary` - Azul con hover
  - `.btn-team-secondary` - Outline gris

- **Estado vacío**:
  - Display grande con opacidad 0.3
  - Texto centrado

- **Animaciones**:
  - Badges: scale(1.05) en hover
  - Focus states mejorados

**Cuándo usar:** En partidos-jugador.html

---

### 10. **foros-listado.css** - Listado de foros
**Propósito:** Sidebar con categorías y lista de foros

**Contenido:**
- `.sidebar-fijo` - 280px, scrollable
- Scrollbar personalizado
- List items con hover azul translúcido
- Botones outline con hover
- Text-truncate con max-width 180px

**Cuándo usar:** En foros-listado.html

---

### 11. **detalleTorneo.css** - Detalle de torneo
**Propósito:** Vista individual de torneo

**Contenido:**
- Hero con imagen de fondo
- Cards de información
- Equipos participantes

**Cuándo usar:** En torneo-detalle.html

---

### 12. **torneo-listado.css** - Listado de torneos
**Propósito:** Grid de torneos disponibles

**Contenido:**
- Grid responsive
- Cards de torneos
- Filtros

**Cuándo usar:** En torneos-listado.html

---

### 13. **style-agenda.css** - Estilos antiguos de agenda
**Propósito:** LEGACY - Estilos originales del calendario

**Nota:** Probablemente duplicado con `pages/agenda.css`. Revisar para consolidar.

---

### 14. **inicio-jugador.css** - Legacy inicio jugador
**Propósito:** LEGACY - Duplicado de `pages/inicioJugador.css`

**Nota:** Consolidar con la versión en `pages/`

---

## Sistema de Colores

### Modo Claro
- **Cards**: `rgba(0, 0, 0, 0.02)` - Gris muy claro
- **Borders**: `rgba(0, 0, 0, 0.08)` - Gris translúcido
- **Overlay**: `rgba(0, 0, 0, 0.3)` - Negro 30%

### Modo Oscuro
- **Cards**: `rgba(255, 255, 255, 0.03)` - Blanco muy translúcido
- **Borders**: `rgba(255, 255, 255, 0.08)` - Blanco translúcido
- **Overlay**: `rgba(0, 0, 0, 0.5)` - Negro 50%
- **Text Secondary**: `#a0aec0` - Gris claro

### Badges y Estados
| Clase | Color | Uso |
|-------|-------|-----|
| `.badge-host` | Verde #198754 | Anfitrión de partido |
| `.badge-guest` | Azul #0d6efd | Invitado |
| `.badge-applicant` | Naranja #fd7e14 | Solicitante |
| `.status-confirmed` | Verde #198754 | Partido confirmado |
| `.status-waiting` | Amarillo #ffc107 | Esperando confirmación |
| `.status-pending` | Gris #6c757d | Pendiente |
| `.status-searching` | Morado #6f42c1 | Buscando rival |

---

## Mejores Prácticas

### BIEN:
1. **Usar variables CSS** para colores y espaciados
2. **Incluir base.css primero**, luego layout.css, luego components.css
3. **Usar clases de Bootstrap** cuando sea posible
4. **Añadir dark mode support** con `[data-bs-theme="dark"]`
5. **Respetar `prefers-reduced-motion`** para accesibilidad
6. **Usar backdrop-filter** para glassmorphism
7. **Transitions suaves** (0.2s ease-in-out)

### EVITAR:
1. **No usar `!important`** a menos que sea absolutamente necesario
2. **No hardcodear colores** - usar variables CSS
3. **No duplicar estilos** entre archivos - consolidar
4. **No olvidar el modo oscuro** al añadir nuevos componentes
5. **No usar position: fixed** sin overflow control

---

## Problemas Comunes y Soluciones

### Dropdown aparece en lugar incorrecto
**Problema:** Dropdown se muestra al final del navbar en lugar de debajo del botón

**Solución:** En `layout.css`:
```css
.navbar .dropdown {
  position: relative; /* NO static */
}

.navbar .dropdown-menu {
  left: 0; /* Alineado a la izquierda del botón */
}
```

### Glassmorphism no funciona
**Problema:** Los backgrounds transparentes se ven sólidos

**Solución:**
```css
background-color: rgba(33, 37, 41, 0.5); /* Usar rgba */
backdrop-filter: blur(10px); /* Añadir blur */
border: 1px solid rgba(255, 255, 255, 0.2); /* Border sutil */
```

### Cards sin hover effect
**Problema:** Las tarjetas no responden al hover

**Solución:** Verificar que:
1. `.card` tenga `transition` definido
2. `.card:hover` tenga `transform` y `box-shadow`
3. No esté dentro de un media query `prefers-reduced-motion`

### Texto ilegible en dark mode
**Problema:** Texto gris sobre fondo oscuro

**Solución:**
```css
[data-bs-theme="dark"] .text-muted {
  color: #a0aec0 !important; /* Gris más claro */
}
```

---

## Orden de Carga Recomendado (en el head.php están incluidos)

```html
<!-- 1. Bootstrap CSS -->
<link rel="stylesheet" href="bootstrap.min.css">

<!-- 2. Variables y fundamentos -->
<link rel="stylesheet" href="src/styles/base.css">

<!-- 3. Estructura de página -->
<link rel="stylesheet" href="src/styles/layout.css">

<!-- 4. Componentes reutilizables -->
<link rel="stylesheet" href="src/styles/components.css">

<!-- 5. Estilos específicos de la página, ESTE SOLO HAY QUE LLAMAR -->
<link rel="stylesheet" href="src/styles/pages/[nombre-pagina].css">
```

**Autor:** Equipo FutMatch  
**Última actualización:** Octubre 15, 2025  
**Versión:** 1.0
