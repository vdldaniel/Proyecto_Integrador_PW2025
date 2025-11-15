DROP VIEW IF EXISTS vista_equipos_jugador;

CREATE OR REPLACE VIEW vista_equipos_jugador AS
SELECT 
    e.id_equipo,
    e.id_lider,
    -- agregar nombre del lider
    e.nombre AS nombre_equipo,
    e.foto AS foto_equipo,
    e.abierto,
    e.clave,
    e.descripcion,

    u.nombre AS nombre_lider,
    u.apellido AS apellido_lider,

    -- Cantidad de integrantes del equipo
    (SELECT COUNT(*) 
     FROM jugadores_equipo je 
     WHERE je.id_equipo = e.id_equipo) AS cantidad_integrantes,
     je.id_jugador,

    -- Cantidad de torneos participados por el equipo
    (SELECT COUNT(DISTINCT t.id_torneo)
     FROM torneos t
     INNER JOIN equipos_torneos et ON t.id_torneo = et.id_torneo
     WHERE et.id_equipo = e.id_equipo) AS torneos_participados,

    -- Cantidad de partidos jugados por el equipo
    (SELECT COUNT(*) 
     FROM equipos_partidos ep
     WHERE ep.id_equipo = e.id_equipo) AS partidos_jugados

FROM equipos e
INNER JOIN usuarios u ON e.id_lider = u.id_usuario;
INNER JOIN jugadores_equipo je ON e.id_equipo = je.id_equipo;
