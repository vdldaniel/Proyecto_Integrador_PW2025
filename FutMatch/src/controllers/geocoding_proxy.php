<?php

/**
 * GEOCODING PROXY - Proxy para peticiones a Nominatim
 * ====================================================
 * Evita problemas de CORS al hacer peticiones desde el navegador
 * a la API de OpenStreetMap Nominatim
 */

header('Content-Type: application/json');

// Permitir solicitudes desde el mismo dominio
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$tipo = $_GET['tipo'] ?? '';
$q = $_GET['q'] ?? '';
$lat = $_GET['lat'] ?? '';
$lon = $_GET['lon'] ?? '';

if ($tipo === 'search' && !empty($q)) {
    // Búsqueda de dirección
    $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
        'format' => 'json',
        'q' => $q,
        'limit' => 1,
        'addressdetails' => 1
    ]);
} elseif ($tipo === 'reverse' && !empty($lat) && !empty($lon)) {
    // Geocodificación inversa
    $url = 'https://nominatim.openstreetmap.org/reverse?' . http_build_query([
        'format' => 'json',
        'lat' => $lat,
        'lon' => $lon,
        'addressdetails' => 1
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// Configurar contexto con User-Agent requerido por Nominatim
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: FutMatch/1.0 (contacto@futmatch.com)\r\n"
    ]
]);

// Hacer la petición
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al contactar el servicio de geocodificación']);
    exit;
}

// Devolver la respuesta
echo $response;
