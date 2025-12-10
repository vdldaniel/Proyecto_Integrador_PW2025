<?php
require_once __DIR__ . '/../../app/config.php';
header('Content-Type: application/json');




$UPLOAD_DIR = __DIR__ . '/../../../../public/assets/images/canchas/banners/'; 
$BASE_URL_FOR_DB = '/public/assets/images/canchas/banners/'; // La URL que se guardará en la DB


if (!is_dir($UPLOAD_DIR)) {
    // Si no existe, intenta crearlo
    if (!mkdir($UPLOAD_DIR, 0777, true)) { 
        echo json_encode(['status' => 'error', 'message' => 'El directorio de subida no existe y no se pudo crear.']);
        exit;
    }
}


function updateBannerUrlInDB($conn, $canchaId, $newBannerUrl) {
    // ESTO ES SOLO UN EJEMPLO. UTILIZA SENTENCIAS PREPARADAS EN PRODUCCIÓN.
    
    // Ejemplo de sentencia preparada (usando PDO o MySQLi):
    // $stmt = $conn->prepare("UPDATE canchas SET banner = ? WHERE id_cancha = ?");
    // $stmt->bind_param("si", $newBannerUrl, $canchaId);
    // return $stmt->execute();
    
    // ** Reemplaza el return true por tu lógica de DB real **
    return true; 
}


// 2. Procesamiento de la Solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

if (!isset($_POST['id_cancha']) || !isset($_FILES['banner_file'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID de cancha o archivo no recibido.']);
    exit;
}

$canchaId = (int)$_POST['id_cancha'];
$file = $_FILES['banner_file'];

// Validación de archivo
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la subida del archivo. Código: ' . $file['error']]);
    exit;
}

$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedMimeTypes)) {
    echo json_encode(['status' => 'error', 'message' => 'Tipo de archivo no permitido. Solo JPEG, PNG o GIF.']);
    exit;
}

// Generar un nombre de archivo único
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = $canchaId . '_' . time() . '.' . $extension; // Ejemplo: 12_1635782400.jpg
$targetPath = $UPLOAD_DIR . $fileName;
$dbPath = $BASE_URL_FOR_DB . $fileName;


// 3. Mover y Guardar en la DB
if (move_uploaded_file($file['tmp_name'], $targetPath)) {


    if (updateBannerUrlInDB(null, $canchaId, $dbPath)) {
        echo json_encode(['status' => 'success', 'message' => 'Banner actualizado con éxito.', 'new_url' => $dbPath]);
    } else {
        // Si falla la DB, puedes intentar borrar el archivo para limpiar
        unlink($targetPath); 
        echo json_encode(['status' => 'error', 'message' => 'El archivo se subió, pero falló la actualización en la base de datos.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al mover el archivo subido. Verifique permisos de carpeta.']);
}
?>