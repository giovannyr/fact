<?php

require_once '../db/Database.php';

class ReportesPantalla extends Database {
    
    private $response;
    
    function __construct() {
        $this->response = array();
    }
    
    function consultar_facturas(){
        $sql = "select fac.fecha as fecha_compra, if(fac.id < 10,concat('00',fac.id),if(fac.id<100,concat('0',fac.id),fac.id)) as numero_factura, rg.Doc_id as documento, concat(rg.Nombre,' ',rg.Seg_nombre,' ',rg.Apellido,' ',rg.Seg_apellido) as nombre_cliente, rg.Empresa as establecimiento, rg.codigo_barras as cod_compras, prd.descripcion as producto, prd.valor_unitario as val_unitario, df.cantidad, df.precio as total, ofer.razon as stand 
                from registro as rg, factura as fac, detalle_factura as df, producto as prd, ofertante as ofer 
                where binary rg.Estado = 'REGISTRADO' and binary rg.codigo_barras <> '' and rg.Doc_id = fac.doc_cliente and fac.id = df.id_factura and df.id_producto = prd.id and prd.id_ofertante = ofer.id 
                order by nombre_cliente";
        $result = $this->set($sql, array());
        if($this->getRow_count() > 0){            
            $this->response['correcto'] = TRUE;
            $this->response['data'] = $result;
            $this->response['msg'] = "";
        }else{
            $this->response['correcto'] = FALSE;
            $this->response['data'] = NULL;
            $this->response['msg'] = "No es posible generar el reporte";
        }
        return $this->response;
    }
    
}
