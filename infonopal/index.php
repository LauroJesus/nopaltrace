<?php
// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
// Permitir métodos GET, POST, PUT, DELETE
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// Permitir encabezados Content-Type, Authorization
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Verificar si el archivo JSON existe, si no, crearlo
if (!file_exists('infonopal.json')) {
    file_put_contents('infonopal.json', '[]');
}

// Leer los datos del archivo JSON
$json_data = file_get_contents('infonopal.json');
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
    $fecha_cultivo = $_POST['fecha_cultivo'];
    $lugar_cultivo = $_POST['lugar_cultivo'];
    $foto_lugar = $_POST['foto_lugar'];
    $link_video = $_POST['link_video'];
    $consumir_antes = $_POST['consumir_antes'];
    $productor_id = $_POST['productor_id'];

    // Generar un nuevo ID autoincrementable
    $new_id = $last_id + 1;

    // Crear un array con los datos de infonopal
    $data = array(
        "id" => $new_id,
        "fecha_cultivo" => $fecha_cultivo,
        "lugar_cultivo" => $lugar_cultivo,
        "foto_lugar" => $foto_lugar,
        "link_video" => $link_video,
        "consumir_antes" => $consumir_antes,
        "productor_id" => $productor_id
    );

    // Agregar los nuevos datos al array existente
    $existing_data[] = $data;

    // Convertir el array a formato JSON
    $new_json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

    // Escribir los datos en el archivo JSON
    file_put_contents('infonopal.json', $new_json_data);

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
