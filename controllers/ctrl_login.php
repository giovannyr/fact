<?php
require_once './utilities.php';

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    require_once '../models/Login.php';
    $lg = new Login();
    $user = normalizar($_POST['user']);
    $pass = normalizar($_POST['password']);
    $response = $lg->consultar($user, $pass);
    die(json_encode($response));
}

?>

