function alerta_atencion(msg){
    $("#msg_alert").empty();
    $("#msg_alert").append("<strong>Atención! </strong>" + msg);
    $("#msg_alert").addClass("alert-yellow");
    $("#mhead").addClass("mh-yellow");
    mostrar();
}

function alerta_error(msg){
    $("#msg_alert").empty();
    $("#msg_alert").append("<strong>Error! </strong>" + msg);
    $("#msg_alert").addClass("alert-red");
    $("#mhead").addClass("mh-red");
    mostrar();
}

function alerta_acierto(msg){  
    $("#msg_alert").empty();
    $("#msg_alert").append("<strong>Correcto! </strong>" + msg);
    $("#msg_alert").addClass("alert-green");
    $("#mhead").addClass("mh-green");
    mostrar();
}

function mostrar(){
    $("#modal_alerts").modal('show');
}