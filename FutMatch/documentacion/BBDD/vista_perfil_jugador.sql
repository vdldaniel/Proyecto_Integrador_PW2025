/* PERFIL JUGADOR

BBDD: USUARIOS
id_usuario, =session ID_USER
email, => no aparece en el perfil, pero sirve para traer el contacto
nombre, => aparece en el perfil
apellido, => aparece en el perfil
id_estado, => aparece en el perfil
fecha_registro => aparece en el perfil (usuario desde...)

BBDD: JUGADOR:
id_jugador, = id_usuario
username, => aparece en el perfil
telefono => no aparece en el perfil, pero sirve para contacto
foto_perfil, => aparece en el perfil
banner => aparece en el perfil
fecha_nacimiento, => aparece en el perfil (edad)
id_sexo, => aparece en el perfil
id_posicion, => NO SE USA
reputación, => aparece en el perfil
*/

DROP VIEW IF EXISTS vista_perfil_jugador;
CREATE VIEW vista_perfil_jugador AS
SELECT 
    u.id_usuario,
    u.nombre,
    u.apellido,
    u.fecha_registro,
    
    j.id_jugador,
    j.username,
    j.telefono,
    j.foto_perfil,
    j.banner,
    j.fecha_nacimiento,
    j.id_sexo,
    j.reputacion,

    -- Sexo
    s.nombre AS sexo,
    
    e.nombre AS estado_usuario

FROM usuarios u
INNER JOIN jugadores j ON u.id_usuario = j.id_jugador
INNER JOIN sexo s ON j.id_sexo = s.id_sexo
INNER JOIN estados_usuarios e ON u.id_estado = e.id_estado;