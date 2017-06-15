<?php ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php require_once 'enlaces.php'; ?>
        <link rel="stylesheet" href="../resources/menu/css/simple-sidebar.css"/>        
    </head>
    <body>
        <?php require './others/alert.php'; ?>
        <?php require_once './others/nav.php'; ?>
        <div id="wrapper">
            <?php require_once './others/sidebar.php'; ?>
            <div id="page-content-wrapper">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col lg-12">
                            <h1 class="page-header">Informes</h1>
                        </div>
                    </div>                   
                    
                    <div class="row">                        
                        <div class="col-lg-3 col-md-3">
                            <form action="../controllers/ctrl_reporte_detallado.php" method="POSt">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-file-excel-o fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <button type="submit" class="btn btn-default">
                                                <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>
                                                Descargar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <span class="pull-left">Informe Detallado</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
			</div>
                                           
                        <div class="col-lg-3 col-md-3">
                            <form action="" method="POSt">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-file-excel-o fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <button type="submit" class="btn btn-default">
                                                <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>
                                                Descargar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <span class="pull-left">Informe Mayor Comprador</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
			</div>
                        
                        <div class="col-lg-3 col-md-3">
                            <form action="" method="POSt">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-file-excel-o fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <button type="submit" class="btn btn-default">
                                                <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>
                                                Descargar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <span class="pull-left">Informe ...</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
			</div>
                        
                        <div class="col-lg-3 col-md-3">
                            <form action="" method="POSt">
                                <div class="panel panel-orange">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-file-excel-o fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <button type="submit" class="btn btn-default">
                                                <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>
                                                Descargar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <span class="pull-left">Informe ...</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
			</div>
                    </div>
                    
                    
                    <div class="row" style="display: none;">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive" style="height: 350px; overflow-y: auto">
                                <table class="table table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Consecutivo</th>
                                            <th>Documento Cliente</th>
                                            <th>Nombre Cliente</th>
                                            <th>Establecimiento</th>
                                            <th>Codigo Compras</th>
                                            <th>Producto</th>
                                            <th>V. Unitario</th>
                                            <th>Cantidad</th>
                                            <th>Total Compra</th>
                                            <th>Stand</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista_compradores">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 

                </div>
            </div>
        </div>               

        <script type="text/javascript" src="../resources/js/ac.js"></script>
        <script type="text/javascript" src="../resources/js/us.js"></script>
        <script type="text/javascript" src="../resources/js/app/reporte.js"></script>        

        <script type="text/template" id="temp_lista_compradores">
            <% _.each(list, function(item){ %> 
            <tr>
                <td><%= item.fecha_compra %></td>
                <td><%= item.numero_factura %></td>                
                <td><%= item.documento %></td>                
                <td><%= item.nombre_cliente %></td>                
                <td><%= item.establecimiento %></td>                
                <td><%= item.cod_compras %></td>                
                <td><%= item.producto %></td>                
                <td><%= accounting.formatMoney(item.val_unitario) %></td>                
                <td><%= item.cantidad %></td>                
                <td><%= accounting.formatMoney(item.total) %></td>                
                <td><%= item.stand %></td>
            </tr>
            <% }); %>
        </script>       

    </body>
</html>  