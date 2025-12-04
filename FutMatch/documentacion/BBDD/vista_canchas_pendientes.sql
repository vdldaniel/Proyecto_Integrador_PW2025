DROP VIEW IF EXISTS vista_canchas_pendientes;
CREATE VIEW vista_canchas_pendientes AS
SELECT
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.telefono AS telefono_cancha,
    c.id_estado,
    c.fecha_creacion,
    c.id_verificador,
    e.nombre AS estado_cancha,
    
    c.id_admin_cancha,
    u.nombre AS nombre_admin,
    u.apellido AS apellido_admin,
    v.nombre AS nombre_verificador,
    v.apellido AS apellido_verificador,
    u.email AS email_admin,
    ac.telefono AS telefono_admin,
    
    d.direccion_completa,
    d.pais,
    d.provincia,
    d.localidad,
    d.latitud,
    d.longitud

FROM canchas c
INNER JOIN estados_canchas e ON c.id_estado = e.id_estado
INNER JOIN usuarios u ON c.id_admin_cancha = u.id_usuario
INNER JOIN usuarios v ON c.id_verificador = v.id_usuario
INNER JOIN admin_canchas ac ON c.id_admin_cancha = ac.id_admin_cancha
INNER JOIN direcciones d ON c.id_direccion = d.id_direccion;