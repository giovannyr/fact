function consultar_listado() {
    $.ajax({
        url: '../controllers/ctrl_reportes.php',
        type: 'POST',
        data: 'request=' + JSON.stringify({'accion': 'listar'}),
        success: function (response) {
            //console.log(response);
            response = JSON.parse(response);
            if(response['correcto']){
                mostrar_listado_compradores(response['data']);
            }else{
                alerta_atencion(response['msg']);
            }
        }
    });
}

function mostrar_listado_compradores(data) {
    var temp = _.template($("#temp_lista_compradores").html());
    $("#lista_compradores").html(temp({list: data}));
}

consultar_listado();