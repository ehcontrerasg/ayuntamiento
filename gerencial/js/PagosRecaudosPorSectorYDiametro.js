//Arreglo que contiene los calibres deseados en el reporte. (Cuando el reporte se clasifica por uso)
var calibresOptimizado    = [{id:0, descripcion:'Sin medidor'},
    {id:1,  descripcion:'1/2"'},
    {id:2,  descripcion:'3/4"'},
    {id:3,  descripcion:'1"'},
    {id:5,  descripcion:'1 1/2"'},
    {id:6,  descripcion:'2"'},
    {id:7,  descripcion:'3"'},
    {id:8,  descripcion:'4"'},
    {id:11, descripcion:'6"'},
    {id:9,  descripcion:'8"'}];

//Arreglo de objetos que contiene los parámetros de cada reporte.
var parametrosTablaOptimizado = [
                                 {reporte: "NumeroPagosAgua",
                                  parametrosTabla: { tableTitle: "Numero de Pagos Agua",
                                                     nombreArchivo: "Numero de Pagos Agua",
                                                     formaClasificacion:"Uso",
                                                     idDivPadre: "dvNumeroPagosAgua",
                                                     idTabla: "tblNumeroPagosAgua"
                                  }},
                                  {reporte: "RecaudosAgua",
                                  parametrosTabla: { tableTitle: "Recaudos Agua",
                                                     nombreArchivo: "Recaudos Agua",
                                                     formaClasificacion:"Uso",
                                                     idDivPadre: "dvRecaudosAgua",
                                                     idTabla: "tblRecaudosAgua"
                                  }},
                                  {reporte: "NumeroPagosPorSector",
                                  parametrosTabla: { tableTitle: "Número de pagos de agua, por sector",
                                                     nombreArchivo: "Número de pagos de agua por sector",
                                                     formaClasificacion:"Sector",
                                                     idDivPadre: "dvNumeroPagosPorSector",
                                                     idTabla: "tblNumeroPagosPorSector"
                                  }},
                                  {reporte: "RecaudosPorSector",
                                  parametrosTabla: { tableTitle: "Recaudos, por sector",
                                                     nombreArchivo: "Recaudos por sector",
                                                     formaClasificacion:"Sector",
                                                     idDivPadre: "dvRecaudosPorSector",
                                                     idTabla: "tblRecaudosPorSector"
                                  }},
                                  {reporte: "ConsumoFacturadoRed",
                                  parametrosTabla: { tableTitle: "Consumo facturado red (m3)",
                                                     nombreArchivo: "Consumo facturado red (m3)",
                                                     formaClasificacion:"Uso",
                                                     idDivPadre: "dvConsumoFacturadoRed",
                                                     idTabla: "tbldvConsumoFacturadoRed"
                                  }},
                                  {reporte: "ConsumoFacturadoPozo",
                                  parametrosTabla: { tableTitle: "Consumo facturado pozo (m3)",
                                                     nombreArchivo: "Consumo facturado pozo (m3)",
                                                     formaClasificacion:"Uso",
                                                     idDivPadre: "dvConsumoFacturadoPozo",
                                                     idTabla: "tblConsumoFacturadoPozo"
                                  }}
                                ];

//Variable que contiene al botón de generar reporte.
var   btnGenerar     = $("#btnGenerar");
//Variable que contiene al select de los reportes.
const slcReportes  = $("select[name=reporte]");

//Arreglo de cada uno de los estilos de los elementos.
var estilos             = {tdTotal:        'border: 1.1px solid black; background-color:#f2f2f2; font-weight:bold;',
                           tdResumenTotal: 'border: 1.1px solid black; background-color:#a0c5c5; font-weight:bold;',
                           borde:     'border: 1.1px solid black;',
                           negrita:   'font-weight:bold;',
                           subrayado: 'text-decoration:underline;',
                           fondoGris: 'background-color:#f2f2f2;',
                           fondoAzul: 'background-color:#a0c5c5;',
                          };

$(document).ready(function(){
    llenarSpinerPro();
    CargarReportesSelect(parametrosTablaOptimizado);
});

//Evento 'click' que genera el reporte en excel.
btnGenerar.on("click",function () {

    var periodoDesde = $("input[name=periodoDesde]").val();
    var periodoHasta = $("input[name=periodoHasta]").val();
    var proyecto     = $("select[name=proyecto]").val();
    var tipReporte   = slcReportes.val();

    //Busca el reporte seleccionado.
    var reporte   = parametrosTablaOptimizado.filter((elemento)=>(elemento.reporte == tipReporte))[0];

   if(reporte!=null){

       var parametrosConsulta = {tip:reporte.reporte,periodoDesde:periodoDesde,periodoHasta:periodoHasta,proyecto:proyecto};
       var parametrosTabla    = reporte.parametrosTabla;

       swal
       ({
               title: "Advertencia!",
               text: "Su reporte se generará dentro de poco tiempo.",
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
               if (isConfirm){
                       let parametros = {parametrosConsulta:parametrosConsulta, parametrosTabla:parametrosTabla};
                       compSession(function(){
                           Generar(parametros);
                       });
               }
           });
   }else{
       swal("Error!", "No se encontró reporte para generar.", "error");
   }

});

/**
 * Funciones por tabla.
 * */

function HtmlToExcel(table,fileName){
    //export data in Chrome or FireFox
    //this works in Chrome as well as in FireFox
    //sa = true;
    var myBlob =  new Blob( [table] , {type:'text/html'});
    var url = window.URL.createObjectURL(myBlob);
    var a = document.createElement("a");
    document.body.appendChild(a);
    a.href = url;
    a.download = fileName+".xls";
    a.click();
    //adding some delay in removing the dynamically created link solved the problem in FireFox
    setTimeout(function() {window.URL.revokeObjectURL(url);},0);
}

function llenarSpinerPro(){

    $.ajax
    ({
        url : "../datos/datos.PagosRecaudosPorSectorYDiametro.php",
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            for(var x=0;x<json.length;x++){
                $('#slcProyecto').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {
            alert(xhr);
        }
    });
}

function CargarReportesSelect(parametrosTabla){

    slcReportes.empty();
    var option ="<option value=''>--Seleccione un reporte--</option>";
    slcReportes.append(option);
    parametrosTabla.forEach(function(parametro){
        option = "<option value='"+parametro.reporte+"'>"+parametro.parametrosTabla.tableTitle+"</option>";
        slcReportes.append(option);
    });
}

function CrearTabla(divPadreId,idTabla,tituloTabla) {
    /**
     * Función que crea la tabla correspondiente a cada archivo excel.
     * */
    var table        = `
                        <!doctype html>
                        <html>
                            <head>
                                <meta charset="UTF-8"/>
                            </head>
                            <body>
                                <table style="border: 2px solid black" id="${idTabla}">
                                <thead>
                                    <!--<tr>
                                        <th style="${estilos.subrayado}">${tituloTabla}</th>
                                    </tr>-->
                                </thead>
                                <tbody></tbody>
                            </table>
                            </body>                           
                        </html>                       
                      `;

    var divPadre = $("#"+divPadreId);
    divPadre.empty();
    divPadre.append(table);
}

function ObtenerSectores(parametros,data){

    let proyecto = parametros.parametrosConsulta.proyecto;
    $.post( "../datos/datos.PagosRecaudosPorSectorYDiametro.php", {tip:"ObtenerSectores",proyecto:proyecto},"json" ).done(function(res){
        data.CLASIFICACIONES = JSON.parse(res).sort();
        GenerarReporte(parametros,data);
    });
}

function ObtenerUsos(parametros,data){

    $.post( "../datos/datos.PagosRecaudosPorSectorYDiametro.php", {tip:"ObtenerUsos"},"json" ).done(function(res){
        data.CLASIFICACIONES = JSON.parse(res).sort();
        GenerarReporte(parametros,data);
    });
}

function Generar(parametros){

    let parametrosConsulta = parametros.parametrosConsulta;
    $.post( "../datos/datos.PagosRecaudosPorSectorYDiametro.php", parametrosConsulta,"json" )
        .done(function(respuesta){
            respuesta = JSON.parse(respuesta);
            GenerarReporteSeleccionadoOptimizado(parametros,respuesta);
        });
}

function GenerarReporteSeleccionadoOptimizado(parametros,data){

    let formaClasificacion = parametros.parametrosTabla.formaClasificacion;
    switch (formaClasificacion){
        case "Sector":
            ObtenerSectores(parametros,data);
            break;
        case "Uso":
            ObtenerUsos(parametros,data);
            break;
    }
}

function GenerarReporte(parametros,data){

    let idDivPadre    = parametros.parametrosTabla.idDivPadre ;
    let idTabla       = parametros.parametrosTabla.idTabla ;
    let tituloTabla   = parametros.parametrosTabla.tableTitle;
    let proyecto      = parametros.parametrosConsulta.proyecto;
    let periodoDesde  = parametros.parametrosConsulta.periodoDesde;
    let periodoHasta  = parametros.parametrosConsulta.periodoHasta;
    let nombreArchivo = parametros.parametrosTabla.nombreArchivo+proyecto+"_"+periodoDesde+"-"+periodoHasta;

    CrearTabla(idDivPadre,idTabla,tituloTabla);
    CabeceraReporte(data,idTabla,tituloTabla);
    CuerpoReporte(data,idTabla);
    PieReporte(data,idTabla);

    //Descargar en un archivo excel.
    var table    = $("#"+idDivPadre).html();
    HtmlToExcel(table,nombreArchivo);
    swal("Éxito!", "Reporte generado exitosamente.", "success");
}

function ObtenerValor(arregloDatos,filtro){

    /**
     * Esta función busca los valores a partir del filtro espeficado, dentro del arreglo del resultado de la consulta.
     * */

    let data = arregloDatos.filter(filtro);
    let cantidadEntradas = Object.entries(data).length;

    if (cantidadEntradas == 0){
        return 0;
    }

    let sumatoria = 0;
    data.forEach(function(elemento){
        sumatoria+=parseInt(elemento.VALOR);
    });

    return sumatoria;
}

function CabeceraReporte(data,idTabla,tituloTabla){
    let periodos = data.PERIODOS;

    let thead = $(`#${idTabla} thead`);
    let tr    = $("<tr/>").appendTo(thead);

    $("<th/>",{"style": estilos.subrayado + estilos.negrita+estilos.borde}).html(tituloTabla).appendTo(tr);

    periodos.forEach(function(periodo){

        //Formatear periodo
        let periodoFormateado = moment(periodo,'YYYYMM')
            .locale('es')
            .format("MMMM-YYYY")
            .toString()
            .toUpperCase();

        $("<td/>", {"style":estilos.borde+estilos.negrita}).html(periodoFormateado).appendTo(tr);
    });

}

function CuerpoReporte(data,idTabla){

    var clasificaciones = data.CLASIFICACIONES;
    var periodos        = data.PERIODOS;

    clasificaciones.forEach(function(clasificacion){
        DibujarCuerpoReporte(data,periodos,calibresOptimizado, clasificacion,idTabla);
    });

}

function DibujarCuerpoReporte(arregloDatos,periodos,calibres, clasificacion,idTabla){
    let data = arregloDatos.DATA;
    let tBody = $(`#${idTabla} tbody`);
    let tr = $("<tr/>");
    let td = $("<td/>");

    //Fila de clasificación, puede ser por sector o por uso.
    td.html(clasificacion).attr({"style":estilos.negrita}).appendTo(tr);
    tr.attr({"style":estilos.tdUso}).appendTo(tBody);

    //El bucle recorre cada uno de los calibres y los periodos.
    // Luego busca los datos mediante filtros en el arreglo da datos que trae la consulta.
    // Lo busca por periodo, calibre y clasificación (por sector o por uso).
    calibres.forEach(function(calibre){
        var trValor = $("<tr/>");
        $("<td/>").attr({"style":estilos.borde}).html(calibre.descripcion).appendTo(trValor);
        periodos.forEach(function(periodo){

            let filtro = ((elemento) => (elemento.PERIODO === periodo && elemento.CALIBRE == calibre.id.toString() && elemento.CLASIFICACION == clasificacion));
            let valor  = ObtenerValor(data,filtro);
            $("<td/>").attr({"style":estilos.borde}).html(valor).appendTo(trValor);
        });
        trValor.appendTo(tBody);
    });

    //Fila de total
    var trTotal = $("<tr/>");
    $("<td/>").attr({"style":estilos.tdTotal}).html("Total").appendTo(trTotal);
    periodos.forEach(function(periodo){
        let filtro = ((elemento) => (elemento.PERIODO === periodo && elemento.CLASIFICACION == clasificacion));
        let total  = ObtenerValor(data,filtro);
        $("<td/>").attr({"style":estilos.tdTotal}).html(total).appendTo(trTotal);
    });
    trTotal.appendTo(tBody);

}

function PieReporte(arregloDatos,idTabla){

    /**
     * Función que llena la fila del resumen total del reporte.
    * */
    let tBody = $(`#${idTabla} tbody`);
    let tr    = $("<tr/>");

    $("<td/>")
        .attr({"style":estilos.tdResumenTotal}) //Añade los estilos
        .html("Resument total") //Añade el contenido HTML
        .appendTo(tr); //Lo agrega a la fila (tr).

    let periodos = arregloDatos.PERIODOS;
    let data     = arregloDatos.DATA;

    periodos.forEach(function(periodo){
        let filtro = ((elemento) => (elemento.PERIODO === periodo));
        let valor = ObtenerValor(data,filtro);
        $("<td/>")
            .attr({"style":estilos.tdResumenTotal})
            .html(valor)
            .appendTo(tr);
    });

    tr.appendTo(tBody);
}


