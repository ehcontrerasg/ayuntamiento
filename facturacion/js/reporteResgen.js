$(document).ready(function(){

        $('#reporte_resgen').on('submit', function(e) {
            e.preventDefault();  //prevent form from submitting
            var datos=$("#reporte_resgen").serializeArray();
            console.log(datos); //use the console for debugging, F12 in Chrome, not alerts
            swal
            ({
                    title: "Advertencia!",
                    text: "¿Desea genererar este reporte ?",
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

    var datos=$("#reporte_resgen").serializeArray();

    $.ajax
    ({
        url: '../datos/datos.reporte_resgen.php',
        type: 'POST',
        data:datos,
        start_time: new Date().getTime(),
        success: function (res) {
            $('#divReporte').empty();
            $('#divReporte').append(res);
            swal
            (
                {
                    title: "Reporte Generado!",
                    text: "Se ha generado el reporte correctamente.<br>Duración: "+((new Date().getTime() - this.start_time)/60000).toFixed(2)+" minutos ",
                    type: "success",
                    html: true,
                    confirmButtonColor: "#66CC33",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: true
                });

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

