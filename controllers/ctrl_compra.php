<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $accion = filter_input(INPUT_POST, 'accion');
    $data = filter_input(INPUT_POST, 'data');
    $response = array();
    
    switch ($accion){
        case 'consultar_cliente':
            require '../models/Compra.php';
            $cm = new Compra();
            $response = $cm->consultar_cliente($data);
            break;
        case 'consultar_producto':
            require '../models/Producto.php';
            $prd = new Producto();
            $response = $prd->consultar_producto($data);
            break;
    }
    die(json_encode($response));
}

?>

