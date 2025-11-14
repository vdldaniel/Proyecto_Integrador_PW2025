-- =========================================================
-- VISTA: Partidos por Jugador
-- =========================================================
-- Esta vista permite consultar los partidos de cualquier jugador
-- simplemente filtrando por id_jugador en el WHERE
--
-- Ejemplo de uso:
-- SELECT * FROM vista_partidos_jugador WHERE id_jugador = 1;
-- SELECT * FROM vista_partidos_jugador WHERE id_jugador = 2 ORDER BY fecha_partido DESC;

DROP VIEW IF EXISTS vista_partidos_jugador;

CREATE VIEW vista_partidos_jugador AS
SELECT 
    -- Información del jugador
    pp.id_jugador,
    j.username as mi_username,
    
    -- Información del partido
    p.id_partido,
    p.id_anfitrion,
    p.abierto,
    
    -- Fecha y hora (de la reserva)
    r.id_reserva,
    DATE_FORMAT(r.fecha, '%d/%m/%Y') AS fecha_partido,
    CASE DAYOFWEEK(r.fecha)
        WHEN 1 THEN 'Domingo'
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        WHEN 7 THEN 'Sábado'
    END as dia_semana,
    TIME_FORMAT(r.hora_inicio, '%H:%i') as hora_partido,
    TIME_FORMAT(r.hora_fin, '%H:%i') as hora_fin,
    r.id_tipo_reserva,
    
    -- Información de la cancha
    c.id_cancha,
    c.nombre as nombre_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa as direccion_cancha,
    d.latitud as latitud_cancha,
    d.longitud as longitud_cancha,
    
    -- Estado de la solicitud del usuario
    es.id_estado,
    es.nombre as estado_solicitud,
    
    -- Rol del usuario en el partido
    rp.id_rol,
    rp.nombre as rol_usuario,
    
    -- Información del tipo de partido
    tp.id_tipo_partido,
    tp.nombre as tipo_partido,
    tp.min_participantes as min_participantes,
    tp.max_participantes as max_participantes,
    
    pp.equipo as equipo_asignado,

    -- Cantidad de participantes por equipo
    (SELECT COUNT(*) FROM participantes_partidos pp_a 
     WHERE pp_a.id_partido = p.id_partido AND pp_a.equipo = 1) AS cant_participantes_equipo_a,
    (SELECT COUNT(*) FROM participantes_partidos pp_b 
     WHERE pp_b.id_partido = p.id_partido AND pp_b.equipo = 2) AS cant_participantes_equipo_b,

    -- Información del equipo del jugador (el equipo al que pertenece)
    e_propio.id_equipo AS id_equipo_del_jugador,
    e_propio.nombre AS nombre_equipo_del_jugador,
    e_propio.foto AS foto_equipo_del_jugador,
    ep_propio.goles_anotados AS goles_mi_equipo,
    ep_propio.es_ganador AS mi_equipo_gano,
    
    -- Información del equipo rival
    e_rival.id_equipo AS id_equipo_rival,
    e_rival.nombre AS nombre_equipo_rival,
    e_rival.foto AS foto_equipo_rival,
    ep_rival.goles_anotados AS goles_equipo_rival,
    ep_rival.es_ganador AS equipo_rival_gano



FROM participantes_partidos pp

-- Join con jugadores para obtener info del usuario
INNER JOIN jugadores j ON pp.id_jugador = j.id_jugador

-- Join con partidos
INNER JOIN partidos p ON pp.id_partido = p.id_partido

-- Join con tipos de partido
INNER JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido

-- Join con estados de solicitud
INNER JOIN estados_solicitudes es ON pp.id_estado = es.id_estado

-- Join con roles de partidos
INNER JOIN roles_partidos rp ON pp.id_rol = rp.id_rol

-- Join con partidos_reservas para obtener la reserva
LEFT JOIN partidos_reservas pr ON p.id_partido = pr.id_partido

-- Join con reservas para obtener fecha y hora
LEFT JOIN reservas r ON pr.id_reserva = r.id_reserva

-- Join con canchas para obtener información de la cancha
LEFT JOIN canchas c ON r.id_cancha = c.id_cancha

-- Join con direcciones para obtener la dirección de la cancha
LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion

-- Join para obtener el equipo PROPIO del jugador
-- (el equipo al que el jugador pertenece en este partido)
LEFT JOIN equipos_partidos ep_propio ON p.id_partido = ep_propio.id_partido 
    AND ep_propio.id_equipo IN (
        SELECT je.id_equipo 
        FROM jugadores_equipos je 
        WHERE je.id_jugador = pp.id_jugador
    )
LEFT JOIN equipos e_propio ON ep_propio.id_equipo = e_propio.id_equipo

-- Join para obtener el equipo RIVAL
-- (el otro equipo que participa en el partido, diferente al del jugador)
LEFT JOIN equipos_partidos ep_rival ON p.id_partido = ep_rival.id_partido 
    AND ep_rival.id_equipo != ep_propio.id_equipo
LEFT JOIN equipos e_rival ON ep_rival.id_equipo = e_rival.id_equipo;