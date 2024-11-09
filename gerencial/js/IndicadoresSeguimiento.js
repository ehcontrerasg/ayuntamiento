$(document).ready(function(){
    $("#btnGenerarReporte").click(function(){
        swal
        ({
            title: "Advertencia!",
            text: "El reporte puede tardar algunos minutos en generarse.",
            type: "info",
            showConfirmButton: true,
            confirmButtonText: "Continuar!",
            cancelButtonText: "Cancelar!",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true
        },function(isConfirm){
            if(isConfirm){
                GenerarReporte();
            }
        })
    });

});



function GenerarReporte(){

    var datos= $("#frmIndicadores").serializeArray() ;
    $.ajax({
        type:"POST",
        url:"../reportes/reporte.SeguimientoInmuebles.php",
        data:datos,
        success:function(res){

            if(res.substr(0,11)=="../../temp/"){
                window.location.href = res;
                swal
                (
                    {
                        title: "Reporte Generado!",
                        text: "Has generado correctamente el reporte",
                        type: "success",
                        html: true,
                        confirmButtonColor: "#66CC33",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true},
                    function(isConfirm)
                    {
                        if (isConfirm) window.close();
                    });
            }
        },
        error:function(xhjs,exception){
            console.log(xhjs+" "+exception);
        }
    });
}

function compSession(callback) {
    $.ajax
    ({
        url: '../../configuraciones/session.php',
        type: 'POST',
        data: {tip: 'sess'},
        dataType: 'json',
        success: function (json) {
            if (json == true) {
                if (callback) {
                    callback();
                }
            } else if (json == false) {
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            window.close();
                        }
                    });
                return false;
            }
        },
        error: function (xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}