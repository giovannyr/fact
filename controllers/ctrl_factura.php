<?php

require_once './utilities.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request = json_decode(filter_input(INPUT_POST, 'request'));
    require '../models/Factura.php';
    $response = array();

    switch ($request->accion) {
        case 'registrar':
            /* TODO: 
             * - hacer validaciones para que no se registre dos veces la 
             * misma factura, y para permitir la impresion de la orden. 
             * utilizar variables de session
             * - obtener el valor del cupo mediante una consulta para evitar 
             * errores y validar si el cupo alcanza para hacer la compra
             */
            $documento = $request->data->cliente->cod_cliente;
            $municipio = $request->data->cliente->municipio;
            $detalles = $request->data->compra;
            $total_compra = calcular_total_compra($detalles);
            $cupo_disp = floatval($request->data->cliente->cupo_disponible) - $total_compra;
            $fac = new Factura();
            $fac->set_data($documento, $municipio, $detalles, $cupo_disp);
            $fac->guardar();

            #echo ($documento);
            #var_dump($detalles); 
            
            $response['valido'] = TRUE;
            $response['datos'] = NULL;
            $response['msg'] = '';
            break;
    }
    die(json_encode($response));
}

function calcular_total_compra($detalles) {
    $total = 0;
    foreach ($detalles as $value) {
        $total += floatval($value->total);
    }
    return $total;
}

?>