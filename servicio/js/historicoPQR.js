

$(document).ready(function(){

    $("#frmCodInmuble").submit(function(){
        var formulario =$("#frmCodInmuble");
        var datos = formulario.serializeArray();

        /*$("#historico_reclamos").load('../vistas/vista.hist_reclamos.php?inmueble=',datos[0]["value"]);*/
        /*$("#historico_reclamos").load('../vistas/vista.hist_reclamos.php',datos);*/


       /* window.open("../vistas/vista.hist_reclamos.php?inmueble="+datos[0]["value"],"","width=200,height=100");*/


        $.get("../vistas/vista.hist_reclamos.php",datos,function(res){

            $("#historico_reclamos").append(res);


        });

    });


});