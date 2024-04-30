<?php
// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
// Permitir métodos GET, POST, PUT, DELETE
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// Permitir encabezados Content-Type, Authorization
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Verificar si el archivo JSON existe, si no, crearlo
if (!file_exists('revendedores.json')) {
    file_put_contents('revendedores.json', '[]');
}

// Leer los datos del archivo JSON
$json_data = file_get_contents('revendedores.json');
$existing_data = json_decode($json_data, true);

// Obtener el último ID para generar un nuevo ID autoincrementable
$last_id = 0;
if (!empty($existing_data)) {
    $last_item = end($existing_data);
    $last_id = $last_item['id'];
}

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $nombre_negocio = $_POST['nombre_negocio'];
    $lugar_ubicacion = $_POST['lugar_ubicacion'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];

    // Generar un nuevo ID autoincrementable
    $new_id = $last_id + 1;

    // Crear un array con los datos del revendedor
    $data = array(
        "id" => $new_id,
        "nombre" => $nombre,
        "nombre_negocio" => $nombre_negocio,
        "lugar_ubicacion" => $lugar_ubicacion,
        "telefono" => $telefono,
        "contrasena" => $contrasena
    );

    // Agregar los nuevos datos al array existente
    $existing_data[] = $data;

    // Convertir el array a formato JSON
    $new_json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

    // Escribir los datos en el archivo JSON
    file_put_contents('revendedores.json', $new_json_data);

    // Responder con un mensaje de éxito
    echo '{"status": 200, "description": "Datos almacenados en JSON correctamente."}';
    die();
}

// Verificar si la solicitud es de tipo GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Responder con los datos en formato JSON
    if ($existing_data !== null) {
        echo json_encode(array("status" => 200, "data" => $existing_data));
    } else {
        echo json_encode(array("status" => 200, "data" => array()));
    }
}
?>
