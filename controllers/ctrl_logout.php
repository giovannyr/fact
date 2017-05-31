<?php
session_start();
unset($_SESSION['nombre_usuario']);
unset($_SESSION['perfil']);
unset($_SESSION['id_ofertante']);
unset($_SESSION['sesion_valida']);
//session_destroy();
header('Location: ../index.php');