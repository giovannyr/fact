<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = filter_input(INPUT_POST, 'accion');
    $data = filter_input(INPUT_POST, 'data');
    require '../models/Compra.php';
    $response = array();

    switch ($accion) {
        case 'consultar_cliente':
            $cm = new Compra();
            $response = $cm->consultar_cliente($data);            
            if($response['correcto']){
                $result2 = $cm->consultar_municipio_cliente($response['data']['cod_cliente']);                
                if($result2['correcto']){
                    $response['datos_municipio'] = $result2['data'];                 
                }
            }
            break;
        case 'consultar_producto':
            $prd = new Producto();
            $response = $prd->consultar_producto($data);
            break;
        case 'listar_productos':
            require_once '../models/Producto.php';
            $prd = new Producto();
            $response = $prd->listar_productos();
            break;
    }
    die(json_encode($response));
}
?>

