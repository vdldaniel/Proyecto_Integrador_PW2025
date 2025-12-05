-- SQL para actualizar torneos para testing de Explorar Torneos
-- Ejecutar en la base de datos futmatch

-- Actualizar torneos existentes para que tengan inscripciones abiertas (id_etapa = 2)
UPDATE torneos 
SET id_etapa = 2,
    max_equipos = 20
WHERE id_torneo IN (1, 2);

-- Verificar los cambios
SELECT 
    t.id_torneo,
    t.nombre,
    t.id_etapa,
    e.nombre AS etapa,
    t.max_equipos,
    (SELECT COUNT(*) FROM equipos_torneos WHERE id_torneo = t.id_torneo AND id_estado = 3) AS total_equipos_inscritos
FROM torneos t
LEFT JOIN etapas_torneo e ON t.id_etapa = e.id_etapa
WHERE t.id_etapa = 2;
