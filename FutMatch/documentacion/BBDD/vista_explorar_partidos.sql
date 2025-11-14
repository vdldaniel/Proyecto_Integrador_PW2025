-- =========================================================
-- VISTA: Explorar Partidos Disponibles
-- =========================================================
-- Muestra partidos abiertos disponibles para que los jugadores soliciten participar
-- Excluye partidos en los que el jugador ya participa (usar filtro en el WHERE del controller)
--
-- Ejemplo de uso:
-- SELECT * FROM vista_explorar_partidos WHERE fecha_partido >= CURDATE() ORDER BY fecha_partido ASC;
-- SELECT * FROM vista_explorar_partidos WHERE id_tipo_partido = 1;

DROP VIEW IF EXISTS vista_explorar_partidos;

CREATE OR REPLACE VIEW vista_explorar_partidos AS
SELECT 
    -- Información del partido
    p.id_partido,
    p.id_anfitrion,
    p.abierto,
    
    -- Información de la reserva
    r.id_reserva,
    r.fecha AS fecha_partido,
    DATE_FORMAT(r.fecha, '%d/%m/%Y') AS fecha_partido_formato,
    CASE DAYOFWEEK(r.fecha)
        WHEN 1 THEN 'Domingo'
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        WHEN 7 THEN 'Sábado'
    END as dia_semana,
    TIME_FORMAT(r.hora_inicio, '%H:%i') as hora_inicio,
    TIME_FORMAT(r.hora_fin, '%H:%i') as hora_fin,
    r.hora_inicio as hora_inicio_raw,
    r.titulo,
    r.descripcion,
    r.id_tipo_reserva,
    
    -- Información de la cancha
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.foto AS foto_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa,
    d.latitud,
    d.longitud,
    
    -- Información del tipo de partido
    tp.id_tipo_partido,
    tp.nombre AS tipo_partido,
    tp.min_participantes,
    tp.max_participantes,
    
    -- Superficie de la cancha
    s.nombre AS tipo_superficie,
    
    -- Información del anfitrión
    j_anfitrion.id_jugador AS id_jugador_anfitrion,
    j_anfitrion.username AS username_anfitrion,
    
    -- Cantidad de participantes actual
    (SELECT COUNT(*) 
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido) AS participantes_actuales,

     -- Cantidad de participantes en pp.equipo=1
    (SELECT COUNT(*)
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido AND pp.equipo = 1) AS cant_participantes_equipo_A,

    -- Cantidad de participantes en pp.equipo=2
    (SELECT COUNT(*)
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido AND pp.equipo = 2) AS cant_participantes_equipo_B

FROM partidos p

-- Unir con partidos_reservas para obtener id_reserva
INNER JOIN partidos_reservas pr ON p.id_partido = pr.id_partido

-- Unir con reservas para obtener fecha, hora y cancha
INNER JOIN reservas r ON pr.id_reserva = r.id_reserva

-- Unir con canchas
INNER JOIN canchas c ON r.id_cancha = c.id_cancha

-- Unir con direcciones
INNER JOIN direcciones d ON c.id_direccion = d.id_direccion

-- Unir con tipo de partido
INNER JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido

-- Unir con superficie de cancha
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie

-- Información del anfitrión
INNER JOIN jugadores j_anfitrion ON p.id_anfitrion = j_anfitrion.id_jugador

-- Condiciones: solo partidos abiertos y futuros
WHERE p.abierto = 1 
  AND r.fecha >= CURDATE()
  AND r.id_tipo_reserva = 1 -- Solo partidos (no torneos)

ORDER BY r.fecha ASC, r.hora_inicio ASC;