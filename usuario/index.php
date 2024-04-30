<?php
// Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Origin: *');
// Permitir métodos GET, POST, PUT, DELETE
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// Permitir encabezados Content-Type, Authorization
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Leer los datos del archivo JSON de productores
    $json_productores = file_get_contents('../productor/data.json');
    $productores = json_decode($json_productores, true);

    // Leer los datos del archivo JSON de infonopal
    $json_infonopal = file_get_contents('../infonopal/infonopal.json');
    $infonopal = json_decode($json_infonopal, true);

    // Leer los datos del archivo JSON de revendedores
    $json_revendedores = file_get_contents('../revendedores/revendedores.json');
    $revendedores = json_decode($json_revendedores, true);

    // Leer los datos del archivo JSON de infonopal_revendedores
    $json_infonopal_revendedores = file_get_contents('../consultaReve_Produc/infonopal_revendedores.json');
    $infonopal_revendedores = json_decode($json_infonopal_revendedores, true);

    // Realiza la consulta para obtener los datos del productor con id 1
    $productor_id = 1;

    // Buscar el productor con el id especificado
    $productor = null;
    foreach ($productores as $p) {
        if ($p['id'] == $productor_id) {
            $productor = $p;
            break;
        }
    }

    if ($productor !== null) {
        // Buscar los datos de infonopal asociados al productor
        $info_nopal_productor = null;
        foreach ($infonopal as $i) {
            if ($i['id'] == $productor['id']) {
                $info_nopal_productor = $i;
                break;
            }
        }

        // Inicializa un arreglo para almacenar los detalles de los revendedores
        $revendedores_productor = array();

        // Buscar los detalles de los revendedores asociados al productor
        foreach ($infonopal_revendedores as $ir) {
            if ($ir['info_nopal_id'] == $productor['id']) {
                // Buscar el revendedor asociado al infonopal_revendedores
                foreach ($revendedores as $r) {
                    if ($r['id'] == $ir['revendedor_id']) {
                        // Agregar los detalles del revendedor al arreglo
                        $revendedores_productor[] = $r;
                    }
                }
            }
        }

        // Agregar los detalles de los revendedores al arreglo del productor
        $productor['info_nopal'] = $info_nopal_productor;
        $productor['revendedores'] = $revendedores_productor;

        // Codificar el arreglo en formato JSON
        $json_response = json_encode($productor, JSON_PRETTY_PRINT);

        // Guardar los datos en un archivo JSON
        file_put_contents('productor_info.json', $json_response);

        // Enviar la respuesta JSON
        echo $json_response;
    } else {
        // Si no se encontró el productor, devolver un mensaje de error JSON
        echo json_encode(array("message" => "No se encontraron datos del productor con id 1"));
    }
}
?>
