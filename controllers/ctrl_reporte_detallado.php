<?php
require_once '../models/ReporteDetallado.php';
$re = new ReporteDetallado();
$re->generar_excel();
?>