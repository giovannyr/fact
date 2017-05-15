var flag_product = false;
var row_producto = {};
var detalle_productos = [];
var row_cliente = {};

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
        response = JSON.parse(response);
        if (response['correcto']) {
            row_cliente = response['data'];
            autocompletar_cliente(row_cliente);
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
    $('#cupo_ini').val(accounting.formatMoney(data['cupo_inicial'])).attr('disabled', true);
    $('#cupo_disp').val(accounting.formatMoney(data['cupo_disponible'])).attr('disabled', true);
    $('#cod_oferta').focus();
}


function borrar_datos_cliente() {
    $('#cod_cliente').val('').attr('disabled', true);
    $('#nombre_cliente').val('').attr('disabled', true);
    $('#razon_cliente').val('').attr('disabled', true);
    $('#cod_vendedor').val('').attr('disabled', true);
    $('#cupo_ini').val('').attr('disabled', true);
    $('#cupo_disp').val('').attr('disabled', true);
}


function validar_cliente(){
    console.log(row_cliente);
    var valido = true;
    if (row_cliente['cod_cliente'] === undefined || row_cliente['cod_cliente'] === "") {
        valido = false;
    }           
    return valido;
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
    console.log(row_producto, " ", typeof row_producto, " ", row_producto['id_producto']);
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


function agregar_producto() { 
    // TODO 
    // validar, para que no se puedan ingresar elementos duplicados
    // validar el cupo disponible con el valor de la compra para permitir la transaccion
    detalle_productos.push(row_producto);
    row_producto = {};
    listar_productos_agregados();    
    borrar_datos_producto();  // LIMPIA EL FORMULARIO DESPUES DE AGREGAR EL PRODUCTO
    $('#cod_oferta').focus();
    flag_product = false;
}


function validar_duplicados(){
    
}


function listar_productos_agregados(){    
    var tmp_prod = _.template($('#tmp_productos').html());    
    $('#lista_productos').html(tmp_prod({"data":detalle_productos}));
    
    $('buton[data-role="delete_item"]').on('click', function(){
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
        }
    });
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
    if(validar_producto()){
        agregar_producto();
    }else{
        alerta_error('No se pudo agregar el producto, intentelo nuevamente.');
        $('#cod_oferta').focus();
    }
});


$('#btn_registrar_compra').on('click', function(){
    validar_cliente();
    registrar_compra_cliente();
});


$(document).ready(function ()
{
    $("#tarjeta").delayPasteKeyUp(function () {
        var cod_cliente = $("#tarjeta").val();
        consultar_datos_cliente(cod_cliente);
    }, 200);
}); 