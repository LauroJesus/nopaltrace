<?php
// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
// Permitir métodos GET, POST, PUT, DELETE
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// Permitir encabezados Content-Type, Authorization
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Verificar si el archivo JSON existe, si no, crearlo
if (!file_exists('data.json')) {
    file_put_contents('data.json', '[]');
}

// Leer los datos del archivo JSON
$json_data = file_get_contents('data.json');
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
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['password'];

    // Generar un nuevo ID autoincrementable
    $new_id = $last_id + 1;

    // Crear un array con los datos del productor
    $data = array(
        "id" => $new_id,
        "nombre" => $nombre,
        "telefono" => $telefono,
        "contrasena" => $contrasena
    );

    // Agregar los nuevos datos al array existente
    $existing_data[] = $data;

    // Convertir el array a formato JSON
    $new_json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

    // Escribir los datos en el archivo JSON
    file_put_contents('data.json', $new_json_data);

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
