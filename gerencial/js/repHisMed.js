/**
 * Created by Jesus Gutierrez on 22/11/2018.
 */

$(document).ready(function(){
    //desContr();
    compSession();
    compSession(llenarSpinerPro);
    //compSession(llenarSelUso);
    //compSession(llenarSelDiametro);

    $("#selProyHisMed").change(
        function(){
            compSession(llenarSelSec);
        }
    );

    $("#selSecHisMed").change(
        function(){
            compSession(llenarSelRut);
        }
    );

    /*$("#selRutHisMed").change(
        function(){
            compSession(llenarSelCli);
        }
    );*/


    $("#genRepHisMedForm").submit(
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
    var datos=$("#genRepHisMedForm").serializeArray();
    $.ajax
    ({
        url : '../reportes/reportes.HistoricoMedidores.php',
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
            $('#selProyHisMed').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selProyHisMed').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
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
        data : { tip : 'selSec',pro:$("#selProyHisMed").val()},
        success : function(json) {
            $('#selSecHisMed').empty();
            $('#selRutHisMed').empty();
            //$('#selCliHisMed').empty();
            $('#selSecHisMed').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selSecHisMed').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
        data : { tip : 'selRut',sec:$("#selSecHisMed").val()},
        success : function(json) {
            $('#selRutHisMed').empty();
            $('#selRutHisMed').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selRutHisMed').append(new Option(json[x]["DESC_RUTA"], json[x]["ID_RUTA"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

/*function llenarSelCli()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selInm',rut:$("#selSecHisMed").val(),sec:$("#selRutHisMed").val()},
        success : function(json) {
            $('#selCliHisMed').empty();
            $('#selCliHisMed').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selCliHisMed').append(new Option(json[x]["DESC_INM"], json[x]["CODIGO_INM"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}*/


/*function llenarSelDiametro()
{

    $.ajax
    ({
        url : '../datos/datos.datRepAnaCon.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selDia' },
        success : function(json) {
            $('#selDiaHisMed').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selDiaHisMed').append(new Option(json[x]["DESC_CALIBRE"], json[x]["COD_CALIBRE"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}*/

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