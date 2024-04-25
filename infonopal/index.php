<?php
/********** Archivo de conexión *******/
include '../conex.php';
$mysqli = conexion();

// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
// Permitir métodos GET, POST, PUT, DELETE
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// Permitir encabezados Content-Type, Authorization
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $fecha_cultivo = $_POST['fecha_cultivo'];
    $lugar_cultivo = $_POST['lugar_cultivo'];
    $foto_lugar = $_POST['foto_lugar'];
    $link_video = $_POST['link_video'];
    $consumir_antes = $_POST['consumir_antes'];
    $productor_id = $_POST['productor_id'];

    // Preparar la consulta SQL para insertar los datos de infonopal
    $sql = "INSERT INTO infonopal(fecha_cultivo, lugar_cultivo, foto_lugar, link_video, consumir_antes, productor_id) 
            VALUES ('$fecha_cultivo', '$lugar_cultivo', '$foto_lugar', '$link_video', '$consumir_antes', '$productor_id')";

    if ($mysqli->query($sql) === TRUE) {
        $data["status"] = 200; 
        echo json_encode($data);
        die();
    } else {
        echo '{"status":500,"description":"Error al insertar datos: ' . $mysqli->error . '"}';
        die();
    }

    // Cerrar la conexión a la base de datos
    $mysqli->close();
}

// Verificar si la solicitud es de tipo GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Preparar la consulta SQL para obtener todos los datos de infonopal
    $sql = "SELECT * FROM infonopal";

    $result = $mysqli->query($sql);
        
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $data['status'] = 200;
        echo json_encode($data);
    } else {
        echo json_encode(array("status" => 200, "data" => array()));
    }

    // Liberar los resultados y cerrar la conexión a la base de datos
    $result->free_result();
    $mysqli->close();
}
?>

