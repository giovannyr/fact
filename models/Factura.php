<?php
require_once '../db/Database.php';
session_start();

class Factura extends Database{
    
    private $response;
    
    function __construct() {
        $this->response = array();
    }
    
    public function guardar_factura($documento, $detalles, $cupo_disp){        
        $sql = "insert into factura (doc_cliente,fecha,hora,usuario) values (?,CURDATE(),CURTIME(),?)";
        $args = array($documento, $_SESSION['nombre_usuario']);
        $result = $this->set($sql, $args);
        $id_fact = intval($this->getLast_id()); 
        $this->guardar_detalles($id_fact, $detalles, $cupo_disp, $documento);
        if($this->getRow_count() == 1){           
            $this->response['correcto'] = TRUE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "";
        }else{
            $this->response['correcto'] = FALSE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "No se pudo guardar la factura";
        }        
    }
    
    public function guardar_detalles($id_factura, $detalles, $cupo_disp, $documento){
        $sql = "CALL sp_insertar_detalle_factura(?, ?, ?, ?)";
        foreach ($detalles as $value){
            $args = array(intval($id_factura), intval($value->id_producto), 
                            intval($value->cantidad), floatval($value->total));
            $result = $this->set($sql, $args);                            
        }
        $this->actualizar_cupo($cupo_disp, $documento);
    }
    
    public function actualizar_cupo($cupo_disp, $documento){
        $sql = "CALL sp_actualizar_cupo_cliente(?, ?)";
        $args = array($cupo_disp, $documento);
        $this->set($sql, $args);
        if($this->getRow_count() == 1){
            
        }else{
            
        }
    }
    
}
