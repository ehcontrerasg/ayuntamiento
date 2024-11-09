var contador_reportes    = 0;  //Variable que incrementa  conforme los reportes se van generando.
var propiedades_reportes = []; //Variable que almacena los parámetros de cada reporte.

$(document).ready(function(){
    llenarSpinerPro();

    $("#btnGenerarReportes").click(function(){

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
            function(isConfirm)
            {
                if (isConfirm)
                {

                    capturarParametros();
                    compSession(generaRepHistoricoFacturacion(propiedades_reportes[0]));
                    compSession(generaRepHistoricoRecaudacion(propiedades_reportes[1]));
                    compSession(genRepUniUsoPerDat(propiedades_reportes[2]));
                    compSession(genRepMetMesAntDat(propiedades_reportes[3]));
                    compSession(genRepMetAnoAntDat(propiedades_reportes[4]));
                    compSession(genRepDeuOfiPerDat(propiedades_reportes[5]));
                    compSession(genRepUniGerUsoConDat(propiedades_reportes[6]));
                    compSession(genRepUsuAlcGerUsoConDat(propiedades_reportes[7]));
                    compSession(genRepInfGraCliDat(propiedades_reportes[8]));
                    compSession(generaRepFacturacionRecaudoDetalladoUso(propiedades_reportes[9]));
                    compSession(genRepResRecEntDat(propiedades_reportes[10]));
                    compSession(generaRepPagosInmueble(propiedades_reportes[11]));
                    compSession(generaRepConsolidado(propiedades_reportes[12]));

                }
            });

    });
});

function llenarSpinerPro()
{
    $.ajax
    ({
        url : '../datos/datos.datRepHisFac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selProyRepCon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selProyRepCon').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function capturarParametros(){

    var proyecto = $("#selProyRepCon option:selected").val();
    var fechaInicial = $("#fecha_inicial").val();
    var fechaFinal = $("#fecha_final").val();

    var periodoInicial=fechaInicial.split("-");
    var periodoFinal=fechaFinal.split("-");

    periodoInicial = periodoInicial[0]+periodoInicial[1];
    periodoFinal = periodoFinal[0]+periodoFinal[1];

    propiedades_reportes=[
        {periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Historico_de_facturación'},
        {fecini:fechaInicial,fecfin:fechaFinal,proyecto:proyecto,nombre_reporte:'Histórico_de_recaudación'},
        {tip:'UniUsoPer',periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Total_de_unidades_por_uso_y_períodos_usuarios_activos'},
        {tip:'MetMesAnt',periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Comparativo_M³_mes_anterior'},
        {tip:'MetMesAnt',periodo:periodoInicial,proyecto:proyecto, nombre_reporte:'Comparativo_M³_año_anterior'},
        {tip:'DeuOfiPer',periodo:periodoInicial,proyecto:proyecto, nombre_reporte:'Deuda_Actual_Oficiales'},
        {tip:'UniGerUsoCon',periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Unidades_por_gerencia_uso_concepto_y_período'},
        {tip:'UsuAlcGerUsoCon',periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Usuarios_alcantarillado_por_gerencia_uso_concepto_y_periodo'},
        {tip:'InfGraCli',periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Informe_mensual_grandes_clientes'},
        {periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Facturación_y_recaudo_detallado_por_uso'},
        {tip:'ResRecEnt',fecini:fechaInicial,fecfin:fechaFinal,proyecto:proyecto,nombre_reporte:'Recaudo_por_entidad'},
        {periodouno:periodoInicial,periododos:periodoFinal,proyecto:proyecto,nombre_reporte:'Comparativo_Pagos_X_inmuebles'},
        {periodo:periodoInicial,proyecto:proyecto,nombre_reporte:'Reporte_consolidado'}
    ]; //Arreglo que contiene lss propiedades que usará cada reporte.

}

//Histórico facturación
function generaRepHistoricoFacturacion(datos){
     $.ajax
    ({
        url : '../reportes/reportes.Historico_facturacion.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.periodo);
            VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

//Histórico recaudación
function generaRepHistoricoRecaudacion(datos){

    $.ajax
    ({
        url : '../reportes/reportes.Historico_recaudacion.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.fecini+datos.fecfin);
            VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    }

    );

}

//Total unidades por uso y período usuarios activos
function genRepUniUsoPerDat(datos){

    var reporte = $.ajax
    ({
        url : '../datos/datos.datRepUniUsoPer.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.periodo);
            VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

    setTimeout(function(){reporte},60000);
}

//Comparativo metro cubico mes anterior
function genRepMetMesAntDat(datos){
    $.ajax
    ({
        url : '../datos/datos.datRepMetMesAnt.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.periodo);
            VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

//Comparativo metro cubico año anterior
function genRepMetAnoAntDat(datos){

    $.ajax
    ({
        url : '../datos/datos.datRepMetAnoAnt.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            VerificaContador(propiedades_reportes);
            descargarDocumento(urlPdf,"Comparativo_M3_Agno_Anterior_"+datos.proyecto+"_"+datos.periodo);

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

//Deuda Actual Oficiales
function genRepDeuOfiPerDat(datos){

    $.ajax
    ({
        url : '../datos/datos.datRepDeuOfiPer.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

                window.location.href = urlPdf;
                VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

//Unidades Por Gerencia, Uso, Concepto y Periodo
function genRepUniGerUsoConDat(datos){
    $.ajax
    ({
        url : '../datos/datos.datRepUniGerUsoCon.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarTablaHTML("Unidades_Gerencia_Uso_Concepto_"+datos.proyecto+"_"+datos.periodo+".xls",urlPdf);
            VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

//Usuarios Alcan Por Gerencia, Uso, Concepto. y Periodo
function genRepUsuAlcGerUsoConDat(datos){
     $.ajax
     ({
         url : '../datos/datos.datRepUsuAlcGerUsoCon.php',
         type : 'POST',
         dataType : 'text',
         data : datos ,
         success : function(urlPdf) {

          descargarTablaHTML("Usuarios_Alcantarillado_Gerencia_Uso_Concepto_"+datos.proyecto+"_"+datos.periodo+".xls",urlPdf);
          VerificaContador(propiedades_reportes);

         },
         error : function(xhr, status) {
             swal("Error!", "error desconocido contacte a sistemas", "error");
         }
     });
}

//Informe Mensual Grandes Clientes
function genRepInfGraCliDat(datos){
    $.ajax
    ({
        url : '../datos/datos.datInfGraCli.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarTablaHTML("Informe_Mensual_Grandes_Clientes_"+datos.proyecto+"_"+datos.periodo+".xls",urlPdf);
            VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

//Facturación y Recaudo Detallado Por Uso
function generaRepFacturacionRecaudoDetalladoUso(datos){

    $.ajax
    ({
        url : '../reportes/reportes.Fac_rec_detallado_uso.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
            descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.periodo);
            VerificaContador(propiedades_reportes);

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

//Resumen De Recaudo Por Entidad
function genRepResRecEntDat(datos){

    $.ajax
    ({
        url : '../datos/datos.datRepResRecEnt.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

          descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.fecini+datos.fecfin);
          VerificaContador(propiedades_reportes);

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });


}

//Comparativo Pagos Inmueble Entre dos Periodos
function generaRepPagosInmueble(datos){
    $.ajax
    ({
        url : '../reportes/reportes.Com_pagos_inmuebles.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
           descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.periodouno+"_"+datos.periododos);
           VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

//Consolidado
function generaRepConsolidado(datos){
    $.ajax
    ({
        url : '../reportes/reportes.Consolidado_Mensual.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {
           descargarDocumento(urlPdf,datos.nombre_reporte+"_"+datos.proyecto+"_"+datos.periodo);
           VerificaContador(propiedades_reportes);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}

//Función que incrementa el contador de reportes y verifica si se generan todos
function VerificaContador(propiedades_reportes){

    contador_reportes++;
    if(contador_reportes==propiedades_reportes.length){
        swal("Mensaje!", "Has Generado correctamente el reporte", "success");
    }

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

function descargarTablaHTML(nombre_archivo,respuesta_ajax){

   $("#dvData").append(respuesta_ajax);
    var str = encodeURIComponent($('#dvData').html());
    var uri = 'data:text/csv;charset=utf-8,' + str;
    var downloadLink = document.createElement("a");
    downloadLink.href = uri;
    downloadLink.download =nombre_archivo;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
    $("#dvData").empty();
}

function descargarDocumento(url,nombre_archivo){
       var a =  document.createElement("a");
       a.setAttribute("href",url);
       a.setAttribute("download",nombre_archivo);
       a.click();
}