<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request = json_decode(filter_input(INPUT_POST, 'request'));
    require_once '../models/ReportesPantalla.php';
    $response = array();

    switch ($request->accion) {
        case 'listar':
            $rep = new ReportesPantalla();
            $response = $rep->consultar_facturas();
            break;
    }
    
    die(json_encode($response));
}

?>
