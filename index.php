<?php

/*
require_once './db/Database.php';
$db = new Database();

$sql = "CALL sp_insert(?,?,?)";
$params = array('1','Nom. puerta','descripcion');
$result = $db->DML($sql, $params);
echo $result->rowCount();

$sql = "CALL SP_CONSULT(?)";
$params = array(6);
$result = $db->DML($sql, $params);
$s = $result->fetch();
var_dump($s['puerta']);
*/

/*date_default_timezone_set('America/Bogota');
echo date('Y-m-d H:m:s');*/

header("Location: views/index.php");


?>