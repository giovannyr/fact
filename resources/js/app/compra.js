var flag_product = false;
var row_producto = {};
var detalle_productos = [];
var row_cliente = {};
var lista_productos;
var copia_lista_productos;

function consultar_datos_cliente(cod_cliente) {
    $.ajax({
        url: '../controllers/ctrl_compra.php',
        type: 'POST',
        data: {"accion": "consultar_cliente", "data": cod_cliente},
        beforeSend: function () {
            $("#tarjeta").attr("disabled", true);
        }
    }).success(function (response) {
        $("#tarjeta").attr("disabled", false);
        //console.log(response);
        response = JSON.parse(response);
        if (response['correcto']) {
            row_cliente = response['data'];
            var municipios = response['datos_municipio'];            
            autocompletar_cliente(row_cliente);
            //TODO agregar el municipio a los datos del cliente cuando se haga la facturacion
            if(typeof municipios !== 'undefined'){
                autocompletar_municipios(municipios);
            }else{
                autocompletar_municipios([{'subcodigo': "UNICO", 'municipio': "UNICO"}]);
            }
        } else {
            alerta_error(response['msg']);
        }
    });
}


function autocompletar_cliente(data) {
    $('#cod_cliente').val(data['cod_cliente']).attr('disabled', true);
    $('#nombre_cliente').val(data['nombre']).attr('disabled', true);
    $('#razon_cliente').val(data['razon_social']).attr('disabled', true);
    $('#cod_vendedor').val(data['cod_vendedor']).attr('disabled', true);
//    $('#cupo_ini').val(accounting.formatMoney(data['cupo_inicial'])).attr('disabled', true);
//    $('#cupo_disp').val(accounting.formatMoney(data['cupo_disponible'])).attr('disabled', true);
    $('#cod_oferta').focus();
}


function autocompletar_municipios(data){
    var temp = _.template($("#temp_municipios").html());
    $("#municipio").html(temp({datos: data}));
}


function borrar_datos_cliente() {
    $('#cod_cliente').val('').attr('disabled', true);
    $('#nombre_cliente').val('').attr('disabled', true);
    $('#razon_cliente').val('').attr('disabled', true);
    $('#cod_vendedor').val('').attr('disabled', true);
//    $('#cupo_ini').val('').attr('disabled', true);
//    $('#cupo_disp').val('').attr('disabled', true);
}


function validar_cliente(){
    console.log(row_cliente);
    var valido = true;
    if (row_cliente['cod_cliente'] === undefined || row_cliente['cod_cliente'] === "") {
        valido = false;
    }           
    return valido;
}


function listar_productos(){
    $.ajax({
        url: '../controllers/ctrl_compra.php',
        type: 'POST',
        data: {"accion": "listar_productos"},        
        success: function (response) {
            //console.log(response);
            response = JSON.parse(response);
            if (response['correcto']) {                                                                
                lista_productos = response['data'];
                set_cant_total();
                mostrar_productos(lista_productos);                                
            } else {
                alerta_error(response['msg']);
            }
        }
    });
}


function set_cant_total(){
    $.each(lista_productos, function(i, obj) {
        obj.cantidad = 1;
        obj.total = parseFloat(obj.valor);        
    });
    copia_lista_productos = JSON.parse(JSON.stringify(lista_productos));
}


function mostrar_productos(data){
    var temp = _.template($('#tmp_ofertas_productos').html());
    $('#ofertas_productos').html(temp({'data': data}));
    
    $('input[data-name="cant"]').on('change', function () {
        var id = $(this).attr('data-id');
        recalcular_total_producto(parseInt($(this).val()), id);        
    });
    
    $('button[data-role="add"]').on('click', function () {          
        var id = $(this).attr('data-id');
        if(typeof row_cliente['cupo_disponible'] !== 'undefined'){            
            row_producto = _.findWhere(copia_lista_productos, {id_producto: id});
            if(validar_producto()){        
                if(validar_duplicados()){
                    if(validar_cupo()){
                        agregar_producto();                            
                    }else{
                        $('#form_producto').trigger('reset');
                        alerta_error('El cupo es insuficiente');
                    }
                }else{
                    $('#form_producto').trigger('reset');
                    alerta_error('El producto ya esta agregado en la lista');
                }
            }else{
                alerta_error('No se puede agregar el producto.');
                $('#cod_oferta').focus();
            }
        }else{
            borrar_producto();
            $('#form_producto').trigger('reset');
            alerta_error('Primero seleccionar un cliente');
        }
        reset_items();
    });
}


function recalcular_total_producto(cantidad, id){
    var row = _.findWhere(copia_lista_productos, {id_producto: id});
    var valor = parseFloat(row.valor);    
    var total = valor * cantidad;
    total = Math.round(total * 100) / 100;
    row.cantidad = cantidad;
    row.total = total;
    copia_lista_productos = _.without(copia_lista_productos, _.findWhere(copia_lista_productos, {id_producto: id}));
    copia_lista_productos.push(row);    
}


function reset_items(){
    copia_lista_productos = JSON.parse(JSON.stringify(lista_productos));;
    mostrar_productos(copia_lista_productos);
}


function consultar_datos_producto(cod_oferta) {
    $.ajax({
        url: '../controllers/ctrl_compra.php',
        type: 'POST',
        data: {"accion": "consultar_producto", "data": cod_oferta},
        beforeSend: function () {
            $("#cod_oferta").attr("disabled", true);
        },
        success: function (response) {
            $("#cod_oferta").attr("disabled", false);
            response = JSON.parse(response);
            if (response['correcto']) {
                row_producto = response['data'];
                flag_product = true;
                autocompletar_producto(row_producto);
            } else {
                alerta_error(response['msg']);
                borrar_datos_producto();
            }
        }
    });
}


function autocompletar_producto(data) {
    $('#desc_oferta').val(data['descripcion_producto']).attr('disabled', true);
    $('#valor_unitario').val(accounting.formatMoney(parseFloat(data['valor']))).attr('disabled', true);
    $('#cantidad').focus();
    calcular_total_producto(parseFloat(data['valor']), parseInt($('#cantidad').val()));
}


function borrar_datos_producto() {
    $('#desc_oferta').val('').attr('disabled', true);
    $('#valor_unitario').val('').attr('disabled', true);
    $('#cantidad').val("1").attr('disabled', false);
    $('#valor_total').val('').attr('disabled', true);
    $('#cod_oferta').val('').attr('disabled', false);
}


function calcular_total_producto(valor, cantidad) {
    var total = valor * cantidad;
    total = Math.round(total * 100) / 100; // dos decimales
    $('#valor_total').val(accounting.formatMoney(total));
    row_producto.total = total;
    row_producto.cantidad = cantidad;
}


function validar_producto() {
    //console.log(row_producto, " ", typeof row_producto, " ", row_producto['id_producto']);
    var valido = true; 
    if (row_producto['id_producto'] === undefined || row_producto['id_producto'] === "") {
        valido = false;
    }
    if (row_producto['cantidad'] === undefined || row_producto['cantidad'] === "") {
        valido = false;
    }        
    if (row_producto['total'] === undefined || row_producto['total'] === "") {
        valido = false;
    }        
    if (row_producto['codigo_producto'] === undefined || row_producto['total'] === "") {
        valido = false;
    }        
    if (row_producto['descripcion_producto'] === undefined || row_producto['total'] === "") {
        valido = false;
    }        
    if (row_producto['valor'] === undefined || row_producto['total'] === "") {
        valido = false;
    }            
    return valido;
}


function validar_duplicados(){
    var cod = row_producto['codigo_producto'];
    if(typeof _.findWhere(detalle_productos, {'codigo_producto' : cod}) === "undefined"){
        return true;
    }else{
        borrar_producto();
        return false;
    }
}


function total_pedido(){
    var total_pedido;
    if(_.size(detalle_productos) > 0){
        total_pedido = 0;
        _.each(detalle_productos, function(prod){
            total_pedido += prod.total;
        });
    }else{
        total_pedido = row_producto['total'];
    }
    return total_pedido;
}


function validar_cupo(){    
    var cdisp = parseFloat(row_cliente['cupo_disponible']);
    return true;
    if(total_pedido() <= cdisp){
        return true;
    }else{
        borrar_producto();
        return false;
    }    
}


function agregar_producto() { 
    // TODO 
    // validar el cupo disponible con el valor de la compra para permitir la transaccion
    detalle_productos.push(row_producto);
    borrar_producto();
    listar_productos_agregados();    
    borrar_datos_producto();  // LIMPIA EL FORMULARIO DESPUES DE AGREGAR EL PRODUCTO
    $('#cod_oferta').focus();
    flag_product = false;
    console.log(JSON.stringify(detalle_productos));
}




function listar_productos_agregados(){    
    var tmp_prod = _.template($('#tmp_productos').html());    
    $('#lista_productos').html(tmp_prod({"data":detalle_productos}));
    
    $('button[data-role="delete_item"]').on('click', function(){
        var id_item = $(this).attr('data-id');
        eliminar_item(id_item);
    });
}


function eliminar_item(id_item){
    var copia_dp = detalle_productos;
    detalle_productos = _.without(copia_dp, _.findWhere(copia_dp, {
        id_producto: id_item
    }));
    listar_productos_agregados();
}


function registrar_compra_cliente(){
    $.ajax({
        url: '../controllers/ctrl_factura.php',
        type: 'POST',
        data: "request="+JSON.stringify({"accion": "registrar", 
            "data": {"cliente": row_cliente, "compra": detalle_productos}}),
        beforeSend: function () {
            //alerta_atencion("Se estan guardando los datos...");
        },
        success: function (response) {
            console.log(response);
            response = JSON.parse(response);
            if(response['valido']){
                window.open("../../pdf/index.html");
            }else{
                alerta_error('No fue posible registrar la compra');
            }
        }
    });
}


function borrar_producto(){
    row_producto = {};
}


//==================================================
//  MANEJO DE EVENTOS
//==================================================
$("#form_producto").on('submit', function (e) {
    e.preventDefault();
});


$("#cod_oferta").keyup(function (e) {
    var code = e.keyCode;
    if (code === 13) {
        var cod = $(this).val().trim();
        if (cod !== "") {
            consultar_datos_producto(cod);
        }
    }
});


/*
$('#cod_oferta').change(function () {    /// REVISAR EVENTO NO ESTA FUNCIONANDO
    if (flag_product) {
        borrar_datos_producto();
        flag_product = false;
    }
});
*/


//  TODO EVENTO, SI BORRA CODIGO DE LA TARJETA BORRAR TODO;


$('#cantidad').on('change', function () {
    if (typeof row_producto['valor'] !== "undefined" && $('#desc_oferta').val().trim() !== ""
            && $('#cod_oferta').val().trim() !== "" && $('#valor_unitario').val().trim() !== "") {
        calcular_total_producto(parseFloat(row_producto['valor']), parseInt($(this).val()));
    }
});


$('#btn_add_product').on('click', function () {
    if(typeof row_cliente['cupo_disponible'] !== 'undefined'){
        if(validar_producto()){        
            if(validar_duplicados()){
                if(validar_cupo()){
                    agregar_producto();                            
                }else{
                    $('#form_producto').trigger('reset');
                    alerta_error('El cupo es insuficiente');
                }
            }else{
                $('#form_producto').trigger('reset');
                alerta_error('El producto ya esta agregado en la lista');
            }
        }else{
            alerta_error('No se puede agregar el producto.');
            $('#cod_oferta').focus();
        }
    }else{
        borrar_producto();
        $('#form_producto').trigger('reset');
        alerta_error('Primero seleccionar un cliente');
    }
});


$('#btn_registrar_compra').on('click', function(){
    validar_cliente();
    row_cliente.municipio = $("#municipio").val();
    registrar_compra_cliente();
});


$(document).ready(function ()
{
    $("#tarjeta").delayPasteKeyUp(function () {
        var cod_cliente = $("#tarjeta").val();
        consultar_datos_cliente(cod_cliente);
    }, 200);
}); 


listar_productos();