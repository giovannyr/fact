<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Inicio</title>
        <?php require 'enlaces.php'; ?>
        <link rel="stylesheet" href="../resources/css/style_login.css"/>        
    </head>
    <body>       
        <?php require './others/alert.php'; ?>
        <div class="login">
            <h1>Acceder</h1>
            <form id="form_login" method="post">
                <input type="text" name="user" placeholder="Usuario" autocomplete="off" data-validetta="required,minLength[4],maxLength[10]"/>
                <input type="password" name="password" placeholder="Contraseña" autocomplete="off" data-validetta="required,minLength[6],maxLength[12]"/>
                <button type="submit" class="btn btn-primary btn-block btn-large">Iniciar Sesion</button>
            </form>
        </div>
        <script type="text/javascript" src="../resources/js/app/login.js"></script>
    </body>
</html>