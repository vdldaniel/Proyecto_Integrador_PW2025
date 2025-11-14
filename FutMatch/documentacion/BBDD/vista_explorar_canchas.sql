-- VISTA PARA EXPLORAR CANCHAS DISPONIBLES PARA RESERVAR
-- Muestra información de las canchas disponibles para reservar por parte de un jugador

DROP VIEW IF EXISTS vista_explorar_canchas;

CREATE OR REPLACE VIEW vista_explorar_canchas AS
SELECT 
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.foto AS foto_cancha,
    c.descripcion AS descripcion_cancha,

    -- Direccion de la cancha
    d.direccion_completa,
    d.latitud,
    d.longitud,

    -- Tipo de superficie
    s.nombre AS tipo_superficie,

    -- Tipo de partido con máximo participantes disponible en esta cancha
    (SELECT tp_max.nombre 
     FROM canchas_tipos_partido ctp
     INNER JOIN tipos_partido tp_max ON ctp.id_tipo_partido = tp_max.id_tipo_partido
     WHERE ctp.id_cancha = c.id_cancha
     ORDER BY tp_max.max_participantes DESC
     LIMIT 1) AS tipo_partido_max,

    (SELECT AVG(r.calificacion) 
     FROM resenias_canchas r
     WHERE r.id_cancha = c.id_cancha) AS calificacion_promedio

FROM canchas c

INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie;

