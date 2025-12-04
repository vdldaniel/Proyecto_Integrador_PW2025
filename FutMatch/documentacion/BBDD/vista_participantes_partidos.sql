DROP VIEW IF EXISTS vista_participantes_partidos;

CREATE VIEW vista_participantes_partidos AS
SELECT 
    pp.id_partido,
    pp.id_jugador,
    j.username AS username_jugador,
    j.telefono AS telefono_jugador,
    u.nombre AS nombre_jugador,
    u.apellido AS apellido_jugador,

    pp.nombre_invitado,
    
    pp.id_rol,
    rp.nombre AS rol_participante,
    
    pp.id_estado,
    e.nombre AS estado_solicitud,

    pp.equipo,
    CASE pp.equipo 
        WHEN 1 THEN 'Equipo A'
        WHEN 2 THEN 'Equipo B'
        ELSE 'Sin asignar'
    END AS equipo_asignado


FROM participantes_partidos pp
INNER JOIN jugadores j ON pp.id_jugador = j.id_jugador
INNER JOIN usuarios u ON pp.id_jugador = u.id_usuario
INNER JOIN roles_partidos rp ON pp.id_rol = rp.id_rol
INNER JOIN estados_solicitudes e ON pp.id_estado = e.id_estado;