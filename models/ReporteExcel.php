<?php
require_once '../db/Database.php';
ini_set('max_execution_time', 300);
ini_set('memory_limit', '-1');
require_once '../resources/excel/PHPExcel.php';

class ReporteExcel extends Database {

    public function generar_excel() {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Itech")
                ->setLastModifiedBy("Itech")
                ->setTitle("Office 2007 XLSX Informe de Compras")
                ->setSubject("Office 2007 XLSX Informe de Compras")
                ->setDescription("Informe especifico de compras")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Report");

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'INFORME')
                ->setCellValue('A3', 'FECHA')
                ->setCellValue('B3', 'CONSECUTIVO')
                ->setCellValue('C3', 'CODIGO CLIENTE')
                ->setCellValue('D3', 'NOMBRE CLIENTE')
                ->setCellValue('E3', 'ESTABLECIMIENTO')
                ->setCellValue('F3', 'ESTABLECIMIENTO')
                ->setCellValue('G3', 'CODIGO COMPRAS')
                ->setCellValue('H3', 'CODIGO OFERTA')
                ->setCellValue('I3', 'OFERTA')
                ->setCellValue('J3', 'VAL. UNITARIO')
                ->setCellValue('K3', 'CANTIDAD')
                ->setCellValue('L3', 'TOTAL COMPRA')
                ->setCellValue('M3', 'STAND')
                ->setCellValue('N3', 'TOTAL COMPRAS CLIENTE');

        $sql = "select fac.fecha as fecha_compra, if(fac.id < 10,concat('00',fac.id),if(fac.id<100,concat('0',fac.id),fac.id)) as numero_factura, rg.Doc_id as documento, concat(rg.Nombre,' ',rg.Seg_nombre,' ',rg.Apellido,' ',rg.Seg_apellido) as nombre_cliente, rg.Empresa as establecimiento, subc.municipio, rg.codigo_barras as cod_compras, prd.codigo as cod_producto, prd.descripcion as producto, prd.valor_unitario as val_unitario, df.cantidad, df.precio as total, ofer.razon as stand 
                from registro as rg, factura as fac, detalle_factura as df, producto as prd, ofertante as ofer, subcodigos as subc 
                where binary rg.Estado = 'REGISTRADO' and binary rg.codigo_barras <> '' and rg.Doc_id = fac.doc_cliente and fac.id = df.id_factura and df.id_producto = prd.id and prd.id_ofertante = ofer.id and subc.subcodigo = fac.municipio_despacho
                order by nombre_cliente";
        $data = $this->set($sql, array());

        $key = 0;
        $documento = "";
        $total_compras_cliente = 0;
        $total_todas_ventas = 0;
        for ($i = 0; $i < count($data); $i++) {
            if(empty($documento)){
                $documento = $data[$i]['documento'];
                $total_compras_cliente += $data[$i]['total'];
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . ($key + 4), $data[$i]['fecha_compra'])
                    ->setCellValue('B' . ($key + 4), "No. ".$data[$i]['numero_factura'])
                    ->setCellValue('C' . ($key + 4), $data[$i]['documento'])
                    ->setCellValue('D' . ($key + 4), $data[$i]['nombre_cliente'])
                    ->setCellValue('E' . ($key + 4), $data[$i]['establecimiento'])
                    ->setCellValue('F' . ($key + 4), $data[$i]['municipio'])
                    ->setCellValue('G' . ($key + 4), $data[$i]['cod_compras'])
                    ->setCellValue('H' . ($key + 4), $data[$i]['cod_producto'])
                    ->setCellValue('I' . ($key + 4), $data[$i]['producto'])
                    ->setCellValue('J' . ($key + 4), $data[$i]['val_unitario'])
                    ->setCellValue('K' . ($key + 4), $data[$i]['cantidad'])
                    ->setCellValue('L' . ($key + 4), $data[$i]['total'])
                    ->setCellValue('M' . ($key + 4), $data[$i]['stand'])
                    ->setCellValue('N' . ($key + 4), "");
            
                $key += 1;
            }else if($documento ==  $data[$i]['documento']){
                $total_compras_cliente += $data[$i]['total'];
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . ($key + 4), $data[$i]['fecha_compra'])
                    ->setCellValue('B' . ($key + 4), "No. ".$data[$i]['numero_factura'])
                    ->setCellValue('C' . ($key + 4), $data[$i]['documento'])
                    ->setCellValue('D' . ($key + 4), $data[$i]['nombre_cliente'])
                    ->setCellValue('E' . ($key + 4), $data[$i]['establecimiento'])
                    ->setCellValue('F' . ($key + 4), $data[$i]['municipio'])
                    ->setCellValue('G' . ($key + 4), $data[$i]['cod_compras'])
                    ->setCellValue('H' . ($key + 4), $data[$i]['cod_producto'])
                    ->setCellValue('I' . ($key + 4), $data[$i]['producto'])
                    ->setCellValue('J' . ($key + 4), $data[$i]['val_unitario'])
                    ->setCellValue('K' . ($key + 4), $data[$i]['cantidad'])
                    ->setCellValue('L' . ($key + 4), $data[$i]['total'])
                    ->setCellValue('M' . ($key + 4), $data[$i]['stand'])
                    ->setCellValue('N' . ($key + 4), "");
            
                $key += 1;
            }else{
                $total_todas_ventas += $total_compras_cliente;
                $objPHPExcel->setActiveSheetIndex(0)                    
                    ->setCellValue('N' . ($key + 4 - 1), $total_compras_cliente);
                
                $documento = $data[$i]['documento'];
                $total_compras_cliente = $data[$i]['total'];
                
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . ($key + 4), $data[$i]['fecha_compra'])
                    ->setCellValue('B' . ($key + 4), "No. ".$data[$i]['numero_factura'])
                    ->setCellValue('C' . ($key + 4), $data[$i]['documento'])
                    ->setCellValue('D' . ($key + 4), $data[$i]['nombre_cliente'])
                    ->setCellValue('E' . ($key + 4), $data[$i]['establecimiento'])
                    ->setCellValue('F' . ($key + 4), $data[$i]['municipio'])
                    ->setCellValue('G' . ($key + 4), $data[$i]['cod_compras'])
                    ->setCellValue('H' . ($key + 4), $data[$i]['cod_producto'])
                    ->setCellValue('I' . ($key + 4), $data[$i]['producto'])
                    ->setCellValue('J' . ($key + 4), $data[$i]['val_unitario'])
                    ->setCellValue('K' . ($key + 4), $data[$i]['cantidad'])
                    ->setCellValue('L' . ($key + 4), $data[$i]['total'])
                    ->setCellValue('M' . ($key + 4), $data[$i]['stand'])
                    ->setCellValue('N' . ($key + 4), "");
                
                $key += 1;
            }            
        }
        
        $total_todas_ventas += $total_compras_cliente;
        $objPHPExcel->setActiveSheetIndex(0)                    
                    ->setCellValue('N' . ($key + 4 - 1), $total_compras_cliente);        

        $cellSum = $key + 4;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('N' . ($cellSum), $total_todas_ventas);


//============================================
//	=====	styles 	=====
//============================================
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:N1');
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $cellSum . ':M' . $cellSum);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        
        
        /*
         *  CAMBIA EL FORMATO DE UNA COLUMNA A NUMERO 
         *
        $objPHPExcel->getActiveSheet()->getStyle('I4:I'.$cellSum)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('K4:K'.$cellSum)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('M4:M'.$cellSum)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        */                                          
        
        

        $objPHPExcel->getActiveSheet()->setTitle('Hoja1');

        $estiloTitulo = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 16,
                'color' => array(
                    'rgb' => '000000'
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $brdStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $estiloInformacion = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => false,
                'color' => array(
                    'rgb' => '000000'
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            )
        );

        $estiloTotal = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'color' => array(
                    'rgb' => '000000'
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            )
        );

        $estiloEndLine = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3F354D')
            )
        );



        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($estiloTitulo);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N' . $objPHPExcel
                        ->getActiveSheet()
                        ->getHighestRow())
                ->applyFromArray($brdStyle);
        $objPHPExcel->getActiveSheet()->getStyle('A4:N' . $objPHPExcel
                        ->getActiveSheet()
                        ->getHighestRow())
                ->applyFromArray($estiloInformacion);
        $objPHPExcel->getActiveSheet()->getStyle('N' . ($cellSum))->applyFromArray($estiloTotal);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $cellSum . ':M' . $cellSum)->applyFromArray($estiloEndLine);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="InformeDetallado.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('../tmpInformes/InformeCompras.xls');
        header('Location: ../tmpInformes/InformeCompras.xls');
        exit;
    }

}
