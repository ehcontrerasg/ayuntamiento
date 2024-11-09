var frmDatosTarjeta = $("#frmDatosTarjeta");

frmDatosTarjeta.on("submit",function(){
    var datos = $(this).serializeArray();
    datos = {name:tip,value:"getDatosTarjeta"};
    getDatosTarjeta(datos);
});


function getDatosTarjeta(datos){

    $.ajax({
             type:"POST",
             url: "../datos/datos.datosTarjeta.php",
             data: datos,
             success:function(res){
                console.log(res);
             },
             error:function (xhrjq,error) {
                 alert(error);
             }

            });
}