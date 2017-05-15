<?php
require_once '../db/Database.php';

class Login extends Database{
    
    private $response;
    
    function __construct() {
        $this->response = array();
    }
    
    function consultar($user, $pass){
        $sql = "CALL sp_login(?, ?)";
        $args = array($user, md5($pass));
        $result = $this->set($sql, $args);        
        if($this->getRow_count() == 1){
            $row = $result;
            session_start();
            $_SESSION['nombre_usuario'] = $row["nombre"];
            $_SESSION['perfil'] = $row['perfil'];
            $_SESSION['sesion_valida'] = TRUE;
            $this->response['link'] = 'compra.php';
            $this->response['msg'] = '';            
            $this->response['valido'] = TRUE;            
        }else{            
            $this->response['link'] = '';
            $this->response['msg'] = 'Verifique los datos. Si no logra iniciar sesion contacte al administrador';
            $this->response['valido'] = FALSE;  
        }
        return $this->response;
    }
    
}
