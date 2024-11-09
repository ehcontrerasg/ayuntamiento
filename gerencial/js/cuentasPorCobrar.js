$(document).ready(function() {

    compSession(llenarSpinerPro);

    $("#btnGenerarReporte").click(function () {

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
            },
            function (isConfirm) {
                if (isConfirm) {
                    var datos = $("#frmCuentasPorCobrar").serializeArray();
                    $.ajax({
                        url: "../reportes/reportes.cuentasPorCobrar.php",
                        type: "POST",
                        data: datos,
                        success: function (urlPdf) {
                            if (urlPdf.substr(0,11)=="../../temp/"){

                                window.location.href = urlPdf;

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
                                        if (isConfirm)
                                        {
                                            window.close();
                                        }
                                    });

                            }else{
                                swal
                                (
                                    {
                                        title: "Error",
                                        text: "Contacte a sistemas",
                                        type: "error",
                                        html: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Ok!",
                                        closeOnConfirm: true});

                            }
                        }
                    })
                }
            });
    });


});


function llenarSpinerPro() {

        $.ajax
        ({
            url: '../datos/datos.datRepHisFac.php',
            type: 'POST',
            dataType: 'json',
            data: {tip: 'selPro'},
            success: function (json) {
                $('#selProyRepCon').append(new Option('', '', true, true));
                for (var x = 0; x < json.length; x++) {
                    $('#selProyRepCon').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
                }
            },
            error: function (xhr, status) {

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