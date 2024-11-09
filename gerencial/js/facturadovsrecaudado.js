$(document).ready(function(){

    compSession();
    compSession(llenarSpinerPro);
    $("#btnLimpiar").click(function(){

        $(".campo").val("");
    });
});

    $("#frmRecaudadovsFacturado").submit(function(){

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
                    compSession(generarRep);
                }
            });
    });


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

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.datRepHisRec.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selProyecto').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
                $('#selProyecto').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
        },
        error : function(xhr, status) {

        }
    });
}

/*function generarRep(){
    var datos= $("#frmRecaudadovsFacturado").serializeArray();
    $.post("../datos/datos.recaudadoVSFacturado.php",datos,function(res){
        console.log(res);
        if (urlPdf.substr(0,11)=="../../temp/"){

            window.location.href = urlPdf;

        }
        },'json');
}*/



function generarRep(){

    var datos= $("#frmRecaudadovsFacturado").serializeArray();
    var proyecto = $("#selProyecto option:selected").text();
    $.post("../datos/datos.recaudadoVSFacturado.php",datos,function(res){

        $("#divData").append(res);




    }).done(function(){

        swal
        (
            {
                title: "Reporte Generado!",
                text: "Has generado correctamente el reporte.",
                type: "success",
                html: true,
                confirmButtonColor: "#66CC33",
                confirmButtonText: "Ok!",
                closeOnConfirm: true,
                closeOnCancel: true},
            function(isConfirm) {
                if (isConfirm) {
                    window.close();
                }
            });

        //creating a temporary HTML link element (they support setting file names)
        var a = document.createElement('a');
        //getting data from our div that contains the HTML table
        var tipo_dato = 'data:application/vnd.ms-excel';
        var div_tabla = document.getElementById('divData');
        var tabla_html = div_tabla.outerHTML.replace(/ /g, '%20');
        a.href = tipo_dato + ', ' + tabla_html;
        //setting the file name
        a.download = 'Facturado vs recaudado '+proyecto+' '+datos[1]["value"]+' '+datos[2]["value"]+'.xls';
        //triggering the function
        a.click();
        //just in case, prevent default behaviour
        //e.preventDefault();

    });



}