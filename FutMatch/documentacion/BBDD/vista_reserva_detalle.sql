DROP VIEW IF EXISTS vista_reserva_detalle;
CREATE VIEW vista_reserva_detalle AS
    SELECT
        r.id_reserva,
        r.id_cancha,
        r.id_tipo_reserva,
        r.fecha,
        r.fecha_fin,
        r.hora_inicio,
        r.hora_fin,
        r.titulo,
        r.descripcion,
        r.id_estado,
        r.fecha_solicitud,

        -- Creador de la reserva (admin que la creó)
        r.id_creador_usuario,
        u_creador.nombre AS nombre_creador,
        u_creador.apellido AS apellido_creador,
        CONCAT(u_creador.nombre, ' ', u_creador.apellido) AS creador_nombre_completo,
        
        -- Titular de la reserva (jugador o persona externa)
        r.id_titular_jugador,
        r.id_titular_externo,
        
        -- Datos del titular si es jugador
        j_titular.username AS username_titular,
        u_titular.nombre AS nombre_titular_jugador,
        u_titular.apellido AS apellido_titular_jugador,
        j_titular.telefono AS telefono_titular_jugador,
        CONCAT(u_titular.nombre, ' ', u_titular.apellido) AS titular_jugador_nombre_completo,
        
        -- Datos del titular si es persona externa
        pe.nombre AS nombre_titular_externo,
        pe.apellido AS apellido_titular_externo,
        pe.telefono AS telefono_titular_externo,
        CONCAT(pe.nombre, ' ', pe.apellido) AS titular_externo_nombre_completo,
        
        -- Nombre completo del titular (jugador o externo)
        CASE
            WHEN r.id_titular_jugador IS NOT NULL THEN CONCAT(u_titular.nombre, ' ', u_titular.apellido)
            WHEN r.id_titular_externo IS NOT NULL THEN CONCAT(pe.nombre, ' ', pe.apellido)
            ELSE 'Sin titular'
        END AS titular_nombre_completo,
        
        -- Teléfono del titular (jugador o externo)
        COALESCE(j_titular.telefono, pe.telefono) AS titular_telefono,
        
        -- Indicador de tipo de reserva
        CASE
            WHEN r.id_titular_jugador IS NOT NULL THEN 'jugador'
            WHEN r.id_titular_externo IS NOT NULL THEN 'externo'
            ELSE NULL
        END AS tipo_titular,

        tr.nombre AS tipo_reserva,
        tr.descripcion AS descripcion_tipo_reserva,
        e.nombre AS estado_reserva,
        c.nombre AS nombre_cancha,
        c.descripcion AS descripcion_cancha
    
    FROM reservas r
    INNER JOIN tipos_reserva tr ON r.id_tipo_reserva = tr.id_tipo_reserva
    INNER JOIN estados_solicitudes e ON r.id_estado = e.id_estado
    INNER JOIN usuarios u_creador ON r.id_creador_usuario = u_creador.id_usuario
    INNER JOIN canchas c ON r.id_cancha = c.id_cancha
    LEFT JOIN jugadores j_titular ON r.id_titular_jugador = j_titular.id_jugador
    LEFT JOIN usuarios u_titular ON j_titular.id_jugador = u_titular.id_usuario
    LEFT JOIN personas_externas pe ON r.id_titular_externo = pe.id_externo;
    