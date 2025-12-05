-- =========================================================
-- VISTA: Partidos por Jugador
-- =========================================================
-- Esta vista permite consultar los partidos de cualquier jugador
-- simplemente filtrando por id_jugador en el WHERE
--
-- Incluye:
-- 1. Partidos donde el jugador participa directamente (participantes_partidos)
-- 2. Partidos de torneos donde el jugador pertenece a un equipo inscrito
--
-- Ejemplo de uso:
-- SELECT * FROM vista_partidos_jugador WHERE id_jugador = 1;
-- SELECT * FROM vista_partidos_jugador WHERE id_jugador = 2 ORDER BY fecha_partido DESC;

DROP VIEW IF EXISTS vista_partidos_jugador;

CREATE VIEW vista_partidos_jugador AS

-- =========================================================
-- PARTE 1: Partidos regulares (donde el jugador participa directamente)
-- =========================================================
SELECT 
    -- Información del jugador
    pp.id_jugador,
    j.username as mi_username,
    
    -- Información del partido
    p.id_partido,
    p.id_anfitrion,
    p.abierto,
    p.goles_equipo_A,
    p.goles_equipo_B,
    P.id_reserva,
    
    -- Fecha y hora (de la reserva)
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
    pp.id_estado as id_estado_participante,
    esp.nombre as estado_participante,
    r.id_estado as id_estado_reserva,
    es.nombre as estado_reserva,
    
    
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

    
    -- NO SE USA
    -- Información del equipo del jugador (el equipo al que pertenece)
    -- e_propio.id_equipo AS id_equipo_del_jugador,
    -- e_propio.nombre AS nombre_equipo_del_jugador,
    -- e_propio.foto AS foto_equipo_del_jugador,
    
    -- Información del equipo rival
    -- e_rival.id_equipo AS id_equipo_rival,
    -- e_rival.nombre AS nombre_equipo_rival,
    -- e_rival.foto AS foto_equipo_rival,

    -- Información del torneo si la hubiere 
    
    -- tabla TORNEOS
    t.id_torneo,
    t.nombre AS nombre_torneo,
    -- t.id_etapa 
    
    -- tabla ETAPAS_TORNEOS
    et.nombre AS etapa_torneo,
    
    -- tabla PARTIDOS_TORNEOS
    -- acá es donde se puede linkear el id_partido con el id_torneo y su info
    pt.id_fase,
    pt.orden_en_fase,
    pt.id_equipo_A,
    pt.id_equipo_B,

    -- TABLA EQUIPOS
    eqa.nombre AS nombre_equipo_A,
    eqa.foto AS foto_equipo_A,
    eqa.descripcion AS descripcion_equipo_A,
    eqb.nombre AS nombre_equipo_B,
    eqb.foto AS foto_equipo_B,
    eqb.descripcion AS descripcion_equipo_B
    

FROM participantes_partidos pp

-- Join con jugadores para obtener info del usuario
INNER JOIN jugadores j ON pp.id_jugador = j.id_jugador

-- Join con partidos
INNER JOIN partidos p ON pp.id_partido = p.id_partido

-- Join con tipos de partido
INNER JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido

-- Join con roles de partidos
INNER JOIN roles_partidos rp ON pp.id_rol = rp.id_rol

-- Join con reservas para obtener fecha y hora
LEFT JOIN reservas r ON p.id_reserva = r.id_reserva

-- Join con estados de solicitud
INNER JOIN estados_solicitudes es ON r.id_estado = es.id_estado
INNER JOIN estados_solicitudes esp ON pp.id_estado = esp.id_estado

-- Join con canchas para obtener información de la cancha
LEFT JOIN canchas c ON r.id_cancha = c.id_cancha

-- Join con direcciones para obtener la dirección de la cancha
LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion

-- Join para obtener el equipo PROPIO del jugador
-- (el equipo al que el jugador pertenece en este partido)
-- LEFT JOIN equipos_partidos ep_propio ON p.id_partido = ep_propio.id_partido 
    -- AND ep_propio.id_equipo IN (
        -- SELECT je.id_equipo 
        -- FROM jugadores_equipos je 
        -- WHERE je.id_jugador = pp.id_jugador
    -- )
-- LEFT JOIN equipos e_propio ON ep_propio.id_equipo = e_propio.id_equipo

-- Join para obtener el equipo RIVAL
-- (el otro equipo que participa en el partido, diferente al del jugador)
-- LEFT JOIN equipos_partidos ep_rival ON p.id_partido = ep_rival.id_partido 
    -- AND ep_rival.id_equipo != ep_propio.id_equipo
-- LEFT JOIN equipos e_rival ON ep_rival.id_equipo = e_rival.id_equipo

-- Join con la tabla PARTIDOS_TORNEOS para obtener info del torneo
LEFT JOIN partidos_torneos pt ON p.id_partido = pt.id_partido

-- Join con la tabla TORNEOS para obtener info del torneo
LEFT JOIN torneos t ON pt.id_torneo = t.id_torneo

-- Join con la tabla ETAPAS_TORNEOS para obtener info de la etapa del torneo
LEFT JOIN etapas_torneo et ON t.id_etapa = et.id_etapa

LEFT JOIN equipos eqa ON pt.id_equipo_A = eqa.id_equipo
LEFT JOIN equipos eqb ON pt.id_equipo_B = eqb.id_equipo

UNION ALL

-- =========================================================
-- PARTE 2: Partidos de torneos (donde el jugador pertenece a un equipo inscrito)
-- =========================================================
SELECT 
    je.id_jugador,
    j.username as mi_username,
    p.id_partido,
    NULL as id_anfitrion,
    0 as abierto,
    p.goles_equipo_A,
    p.goles_equipo_B,
    p.id_reserva,
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
    c.id_cancha,
    c.nombre as nombre_cancha,
    d.direccion_completa as direccion_cancha,
    d.latitud as latitud_cancha,
    d.longitud as longitud_cancha,
    NULL as id_estado_participante,
    NULL as estado_participante,
    r.id_estado as id_estado_reserva,
    es.nombre as estado_reserva,
    NULL as id_rol,
    'Jugador de Equipo' as rol_usuario,
    tp.id_tipo_partido,
    tp.nombre as tipo_partido,
    tp.min_participantes as min_participantes,
    tp.max_participantes as max_participantes,
    CASE 
        WHEN pt.id_equipo_A = je.id_equipo THEN 1
        WHEN pt.id_equipo_B = je.id_equipo THEN 2
    END as equipo_asignado,
    (SELECT COUNT(*) FROM jugadores_equipos 
     WHERE id_equipo = pt.id_equipo_A AND estado_solicitud = 3) AS cant_participantes_equipo_a,
    (SELECT COUNT(*) FROM jugadores_equipos 
     WHERE id_equipo = pt.id_equipo_B AND estado_solicitud = 3) AS cant_participantes_equipo_b,
    t.id_torneo,
    t.nombre AS nombre_torneo,
    ft.nombre AS etapa_torneo,
    pt.id_fase,
    pt.orden_en_fase,
    pt.id_equipo_A,
    pt.id_equipo_B,
    eqa.nombre AS nombre_equipo_A,
    eqa.foto AS foto_equipo_A,
    eqa.descripcion AS descripcion_equipo_A,
    eqb.nombre AS nombre_equipo_B,
    eqb.foto AS foto_equipo_B,
    eqb.descripcion AS descripcion_equipo_B
FROM jugadores_equipos je
INNER JOIN jugadores j ON je.id_jugador = j.id_jugador
INNER JOIN equipos_torneos et_inscripcion 
    ON je.id_equipo = et_inscripcion.id_equipo 
    AND et_inscripcion.id_estado = 3
INNER JOIN partidos_torneos pt 
    ON et_inscripcion.id_torneo = pt.id_torneo
    AND (pt.id_equipo_A = je.id_equipo OR pt.id_equipo_B = je.id_equipo)
INNER JOIN partidos p ON pt.id_partido = p.id_partido
INNER JOIN reservas r ON p.id_reserva = r.id_reserva
LEFT JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido
INNER JOIN estados_solicitudes es ON r.id_estado = es.id_estado
INNER JOIN torneos t ON pt.id_torneo = t.id_torneo
INNER JOIN fases_torneo ft ON pt.id_fase = ft.id_fase
LEFT JOIN canchas c ON r.id_cancha = c.id_cancha
LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion
LEFT JOIN equipos eqa ON pt.id_equipo_A = eqa.id_equipo
LEFT JOIN equipos eqb ON pt.id_equipo_B = eqb.id_equipo
WHERE je.estado_solicitud = 3

ORDER BY fecha_partido DESC, hora_partido DESC;

-- =========================================================