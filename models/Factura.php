<?php
require_once '../db/Database.php';
session_start();

class Factura extends Database{
    
    private $response;
    private $id_fact;
    private $documento;
    private $detalles;
    private $cupo_disponible;
    private $factura_valida;
    private $detalles_validos;
    private $cupo_valido;
    
    function __construct() {
        $this->response = array();
        $this->id_fact = NULL;
        $this->documento = "";
        $this->municipio = "";
        $this->detalles = NULL;
        $this->cupo_disponible = NULL;
        $this->factura_valida = FALSE;
        $this->detalles_validos = FALSE;
        $this->cupo_valido = FALSE;
    }
    
    public function set_data($documento, $municipio, $detalles, $cupo_disp){
        $this->documento = $documento;
        $this->municipio = $municipio;
        $this->detalles = $detalles;
        $this->cupo_disponible = $cupo_disp;               
    } 
    
    public function guardar() {
        $this->guardar_factura();
        if($this->factura_valida){ // si se inserto la factura
            if(!empty($this->id_fact)){ // si el id no es vacio
                $this->guardar_detalles($this->id_fact, $this->detalles);
                if($this->detalles_validos){    
                    $this->actualizar_cupo($this->cupo_disponible, $this->documento);                
                    $this->response;
                }else{
                    $this->response;
                }
            }else{
                $this->response;
            }
        }else{
            $this->response;
        }
    }
    
    public function guardar_factura(){        
        $sql = "INSERT INTO FACTURA (doc_cliente,fecha,hora,municipio_despacho,usuario) VALUES (?,CURDATE(),CURTIME(),?,?)";
        $args = array($this->documento, $this->municipio, $_SESSION['nombre_usuario']);
        $this->set($sql, $args);
        if($this->getRow_count() == 1){
            $this->factura_valida = TRUE;
            $this->id_fact = intval($this->getLast_id()); 
            
        }else{
            $this->factura_valida = FALSE;
            $this->id_fact = NULL;            
        }        
    }
    
    public function guardar_detalles($id_factura, $detalles){
        $id = intval($id_factura);
        $this->detalles_validos = TRUE;
        $sql = "CALL sp_insertar_detalle_factura(?, ?, ?, ?)";
        foreach ($detalles as $value){
            $args = array($id, intval($value->id_producto), 
                            intval($value->cantidad), floatval($value->total));
            $this->set($sql, $args);             
            if($this->getRow_count() != 1){
                $sql2 = "DELETE FROM factura WHERE id = ?";     // borrar factura con id
                $this->set($sql2, array($id));
                $this->detalles_validos = FALSE;
                break;
            }
        }        
    }
    
    public function actualizar_cupo($cupo_disp, $documento){
        $sql = "CALL sp_actualizar_cupo_cliente(?, ?)";
        $args = array($cupo_disp, $documento);
        $this->set($sql, $args);
        if($this->getRow_count() == 1){
            $this->cupo_valido = TRUE;
        }else{
            $this->cupo_valido = FALSE;
        }
    }
    
}


/*
 * $this->response['correcto'] = TRUE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "";
 */