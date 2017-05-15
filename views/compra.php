<?php
date_default_timezone_set('America/Bogota');

?>

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
                            <h1 class="page-header">Registrar Compra</h1>
                        </div>

                    </div>

                    <div class="row">
                        <form>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="tarjeta">Tarjeta</label>
                                    <input type="text" class="form-control input-sm" name="tarjeta" id="tarjeta" placeholder="Codigo" autofocus="on">
                                </div>                                                                                               
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="cod_cliente">Codigo Cliente</label>
                                    <input type="text" class="form-control input-sm" name="cod_cliente" id="cod_cliente" disabled="true">
                                </div>                                                                                               
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="nombre_cliente">Nombre Cliente</label>
                                    <input type="text" class="form-control input-sm" name="nombre_cliente" id="nombre_cliente" disabled="true">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="razon_cliente">Razon Social</label>
                                    <input type="text" class="form-control input-sm" name="razon_cliente" id="razon_cliente" disabled="true">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="cod_vendedor">Codigo Vendedor</label>
                                    <input type="text" class="form-control input-sm" name="cod_vendedor" id="cod_vendedor" disabled="true">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="cupo_ini">Cupo Inicial</label>
                                    <input type="text" class="form-control input-sm" name="cupo_ini" id="cupo_ini" disabled="true">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="cupo_disp">Cupo Disponible</label>
                                    <input type="text" class="form-control input-sm" name="cupo_disp" id="cupo_disp" disabled="true">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <form id="form_producto">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="cod_oferta">Código Oferta</label>
                                    <input type="text" class="form-control input-sm" name="cod_oferta" id="cod_oferta" placeholder="Oferta">
                                </div> 
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="desc_oferta">Descripción</label>
                                    <input type="text" class="form-control input-sm" name="desc_oferta" id="desc_oferta" disabled="true">
                                </div> 
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad</label>
                                    <input type="number" class="form-control input-sm" name="cantidad" id="cantidad" value="1" placeholder="Cantidad" min="1">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="valor_unitario">V. Unitario</label>
                                    <input type="text" class="form-control input-sm" name="valor_unitario" id="valor_unitario" min="1" disabled="true">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="valor_total">V. Total</label>
                                    <input type="text" class="form-control input-sm" name="valor_total" id="valor_total" min="1" disabled="true">
                                </div>
                            </div>                            
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary btn-sm" id="btn_add_product">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                    <br />
                    <div class="row">
                        <div class="table-responsive" style="height: 210px; overflow-y: auto">
                            <table class="table table-striped table-hover table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cod. Oferta</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>V. Unitario</th>
                                        <th>V. Total</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="lista_productos">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-lg-8">
                            <button id="btn_registrar_compra" class="btn btn-success">Registrar Compra</button>
                            <button id="" class="btn btn-default">NULL</button>
                            <button id="" class="btn btn-default">NULL</button>
                            <button id="" class="btn btn-default">NULL</button>
                        </div>
                        <div class="col-lg-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../resources/js/ac.js"></script>
        <script type="text/javascript" src="../resources/js/us.js"></script>
        <script type="text/javascript" src="../resources/js/app/compra.js"></script>
        <script type="text/template" id="tmp_productos">
            <% _.each(data, function(item){ %>                
                <tr>
                    <td><%= item.codigo_producto %></td>
                    <td><%= item.descripcion_producto %></td>
                    <td><%= item.cantidad %></td>
                    <td><%= accounting.formatMoney(item.valor) %></td>
                    <td><%= accounting.formatMoney(item.total) %></td>
                    <td>                        
                        <buton data-role="delete_item" data-id="<%= item.id_producto %>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></buton>
                    </td>
                </tr>
            <% }); %>
        </script>
    </body>
</html>  