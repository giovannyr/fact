<?php
require_once '../db/Database.php';

class Compra extends Database{
    
    private $response;
    
    function __construct() {
        $this->response = array();
    }
    
    function consultar_cliente($codigo){
        $sql = "CALL sp_consultar_cliente(?)";
        $args = array($codigo);
        $result = $this->set($sql, $args);
        if($this->getRow_count() == 1){
            $row = $result[0];
            $this->response['correcto'] = TRUE;
            $this->response['data'] = $row;
            $this->response['msg'] = "";
        }else{
            $this->response['correcto'] = FALSE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "No se encontro ningun cliente con ese codigo";
        }
        return $this->response;
    }
    
    function consultar_municipio_cliente($codigo_cliente){
        $sql = "SELECT * FROM subcodigos WHERE BINARY codigo = ?";
        $args = array($codigo_cliente);
        $result = $this->set($sql, $args);
        if($this->getRow_count() > 0){            
            $this->response['correcto'] = TRUE;
            $this->response['data'] = $result;
            $this->response['msg'] = "";
        }else{
            $this->response['correcto'] = FALSE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "No hay municipios";
        }
        return $this->response;
    }
    
}
