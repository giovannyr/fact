function enviar(data) {
    $.ajax({
        url: "../controllers/ctrl_login.php",
        type: 'POST',
        data: data
    }).success(function (response) {
        console.log(response)
        response = JSON.parse(response);
        if(response["valido"]){
            window.location = response["link"];
        }else{            
            alerta_error(response["msg"]);
        }
    });
}


function getData(){
    var data = $("#form_login").serializeObject();
    enviar(data);
};
    
    
$(document).ready(function(){    
    $("#form_login").validetta({
        showErrorMessages : true,
        display : 'bubble', 
        errorTemplateClass : 'validetta-bubble',
        errorClass : 'validetta-error',
        validClass : 'validetta-valid', 
        bubblePosition: 'right', 
        bubbleGapLeft: 15, 
        bubbleGapTop: 0, 
        realTime : true,
        validators: {},         
        onValid : function(event){
            event.preventDefault();
            getData();
        },
        onError : function(){}
    });       
});
