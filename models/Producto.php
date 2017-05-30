<?php
session_start();
require_once '../db/Database.php';

class Producto extends Database{
    
    private $response;
    
    function __construct() {
        $this->response = array();
    }
    
    function consultar_producto($codigo){
        $sql = "CALL sp_consultar_producto(?)";
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
            $this->response['msg'] = "No se encontro ningun producto con ese codigo";
        }
        return $this->response;
    }
    
    
    function listar_productos(){
        $sql = "CALL sp_listar_productos(?)";
        $args = array($_SESSION['id_ofertante']);
        $result = $this->set($sql, $args);        
        if($this->getRow_count() > 0){
            $this->response['correcto'] = TRUE;
            $this->response['data'] = $result;
            $this->response['msg'] = "";
        }else{
            $this->response['correcto'] = FALSE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "No es posible mostrar los productos";
        }
        return $this->response;
    }
    
}
