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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// Realiza la consulta SQL
$sql = "SELECT 
            p.id AS productor_id,
            p.nombre AS nombre_productor,
            i.id AS info_nopal_id,
            i.fecha_cultivo,
            i.lugar_cultivo
        FROM Productor p
        JOIN InfoNopal i ON p.id = i.productor_id
        WHERE p.id = 1";

$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Almacena los resultados en un arreglo asociativo
    $data = $result->fetch_assoc();

    // Inicializa un arreglo para almacenar los detalles de los revendedores
    $revendedores = array();

    // Consulta SQL para obtener los detalles de los revendedores asociados
    $sql_revendedores = "SELECT 
                            r.id AS revendedor_id,
                            r.nombre AS nombre_revendedor,
                            r.nombre_negocio,
                            r.lugar_ubicacion
                        FROM Revendedores r
                        JOIN InfoNopal_Revendedores ir ON r.id = ir.revendedor_id
                        WHERE ir.info_nopal_id = " . $data['info_nopal_id'];

    $result_revendedores = $mysqli->query($sql_revendedores);

    if ($result_revendedores->num_rows > 0) {
        // Procesa los resultados de los revendedores y los agrupa por fila principal
        while ($row_revendedor = $result_revendedores->fetch_assoc()) {
            $revendedores[] = $row_revendedor;
        }
    }

    // Agrega los detalles de los revendedores al arreglo principal
    $data['revendedores'] = $revendedores;

    // Codifica el arreglo asociativo en formato JSON
    $json_response = json_encode($data);

    // Envía la respuesta JSON
    echo $json_response;
} else {
    // Si no se encontraron resultados, devuelve un mensaje JSON vacío
    echo json_encode(array("message" => "No se encontraron datos"));
}

// Liberar los resultados y cerrar la conexión a la base de datos
$result->free_result();
$mysqli->close();
}
?>