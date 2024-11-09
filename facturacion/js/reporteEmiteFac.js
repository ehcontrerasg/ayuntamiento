$(document).ready(function(){

    $('#reporte_emitefac').on('submit', function(e) {
        e.preventDefault();  //prevent form from submitting
        swal
        ({
                title: "Aviso!",
                text: "El reporte puede demorar unos minutos en salir.\n¿Desea generarlo?",
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Aceptar',
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    generarReporte();
                }

            });
    });
});

function generarReporte() {

    var datos=$("#reporte_emitefac").serializeArray();

    $.ajax
    ({
        url: '../datos/datos.reporte_emitefac.php',
        type: 'POST',
        data:datos,
        start_time: new Date().getTime(),
        success: function (res) {
            console.log(res);
            console.log(res.substr(0,24));
            if (res.substr(0,24)=="../archivos_facturacion/"){

                swal
                (
                    {
                        title: "Reporte Generado!",
                        text: "Se descargará automáticamente.<br>Duración: "+((new Date().getTime() - this.start_time)/60000).toFixed(2)+" minutos ",
                        type: "success",
                        html: true,
                        confirmButtonColor: "#66CC33",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    });

                var descarga = document.getElementById('descargaReporte');
                descarga.href="../datos/"+res;
               // descarga.download=true;
                descarga.click();

            }else{
                swal
                (
                    {
                        title: "Error",
                        text: "La ruta del archivo es inválida. Contacte a sistemas",
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true});

            }


        },
        error: function (xhr, status) {
            swal
            (
                {
                    title: "Error",
                    text: "Se ha producido un error inesperado. Comuniquese con sistema. status: "+status,
                    type: "error",
                    html: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: true});
        }

    });
}



