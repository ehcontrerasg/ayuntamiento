/**
 * Created by Jesus Gutierrez on 22/11/2018.
 */

$(document).ready(function(){
    //desContr();
    compSession();
    compSession(llenarSpinerPro);
    compSession(llenarSelUso);
    compSession(llenarSelDiametro);

    $("#selProyAnaCon").change(
        function(){
            compSession(llenarSelSec);
        }
    );

    $("#selSecAnaCon").change(
        function(){
            compSession(llenarSelRut);
        }
    );


    $("#genRepAnaConForm").submit(
        function(){
            swal
            ({
                    title: "Advertencia!",
                    text: "El reporte puede tardar algunos minutos en generarse.",
                    type: "info",
                    showConfirmButton: true,
                    confirmButtonText: "Continuar!",
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        compSession(generaRep);
                    }
                });
        }


    )

});

function generaRep(){
    var datos=$("#genRepAnaConForm").serializeArray();
    $.ajax
    ({
        url : '../reportes/reportes.AnalisisConsumoNoMed.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

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

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selProyAnaCon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selProyAnaCon').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}




function llenarSelSec()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selSec',pro:$("#selProyAnaCon").val()},
        success : function(json) {
            $('#selSecAnaCon').empty();
            $('#selRutAnaCon').empty();
            $('#selSecAnaCon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selSecAnaCon').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelRut()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selRut',sec:$("#selSecAnaCon").val()},
        success : function(json) {
            $('#selRutAnaCon').empty();
            $('#selRutAnaCon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selRutAnaCon').append(new Option(json[x]["DESC_RUTA"], json[x]["ID_RUTA"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelUso()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUso' },
        success : function(json) {
            $('#selUsoAnaCon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selUsoAnaCon').append(new Option(json[x]["DESC_USO"], json[x]["ID_USO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelDiametro()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selDia' },
        success : function(json) {
            $('#selDiaAnaCon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selDiaAnaCon').append(new Option(json[x]["DESC_CALIBRE"], json[x]["COD_CALIBRE"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu",function(e){return false;});

}

function compSession(callback)
{
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data:{tip : 'sess'},
        dataType : 'json',
        success : function(json) {
            if(json==true){
                if(callback){
                    callback();
                }
            }else if(json==false){
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();
                        }
                    });
                return false;
            }
        },
        error : function(xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}