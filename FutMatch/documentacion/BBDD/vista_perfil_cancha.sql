/*PERFIL CANCHA

BBDD: CANCHA
id_cancha, => sale del boton "ver perfil cancha"
id_admin_cancha => NO aparece en el perfil, pero sirve para contacto
nombre_cancha, => aparece en el perfil
id_direccion => se usa para buscar la direccion en tabla DIRECIONES
descripcion => aparece en el perfil
id_estado => aparece en el perfil (determina si mostrarse o no)
foto => aparece en el perfil (FOTO_PERFIL)
banner => aparece en el perfil
id_superficie => se usa para buscar el tipo de superficie en tabla superficies_canchas
politicas_reservas => NO aparece en el perfil, pero sirve para AGENDA.

BBDD: DIRECCIONES
id_direccion, = id_direccion de la cancha
direccion_completa => aparece en el perfil
latitud => NO aparece en el perfil, pero sirve para el mapa
longitud => NO aparece en el perfil, pero sirve para el mapa

BBDD: SUPERFICIES_CANCHAS
id_superficie, = id_superficie de la cancha
nombre => aparece en el perfil
*/

DROP VIEW IF EXISTS vista_perfil_cancha;
CREATE VIEW vista_perfil_cancha AS
SELECT
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.descripcion AS descripcion_cancha,
    c.foto AS foto_cancha,
    c.banner AS banner_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa AS direccion_cancha,
    d.latitud AS latitud_cancha,
    d.longitud AS longitud_cancha,
    
    -- Tipo de superficie
    s.nombre AS tipo_superficie

FROM canchas c
INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie;

