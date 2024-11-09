
$(document).ready(function(){


    $('#dif_cortereco').on('submit', function(e) {
        e.preventDefault();
                    generarReporte();
    });


});


function generarReporte(datos) {

    var datos=$("#dif_cortereco").serializeArray();
    var proyecto=datos[0]["value"];
    var fecini=datos[1]["value"];
    var fecfin=datos[2]["value"];

/*   datatable con scroll:

 var dataTable =  $('#dataTable').DataTable( {
        serverSide: true,
        ajax:{
            url :"../datos/datos.diferidos_corte_reco.php",
            type: "post",
            data:{proyecto:proyecto,
                fecini:fecini,
                fecfin:fecfin,
            },
            buttons: [
                        { extend: 'copy', text:' Copiar', className: 'btn btn-primary glyphicon glyphicon-duplicate' },
                        { extend: 'csv',  text:' CVS', className: 'btn btn-primary glyphicon glyphicon-save-file' },
                        { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt' },
                        { extend: 'pdf',  text:' PDF',  className: 'btn btn-primary glyphicon glyphicon-file' },
                        {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' }
            ],
            error: function(){  // error handling
                $(".employee-grid-error").html("");
                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("display","none");
            }
        },
        dom: "BlfrtiS",
        scrollY: 370,
        deferRender: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        language:  {url: '../../js/DataTables-1.10.15/Spanish.json'},
        scroller: {
            loadingIndicator: true
        }
    } );*/

    //exportarDatos(proyecto,fecini,fecfin);


    // con paginacion:

    if ($.fn.dataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().destroy();
    }

    var dataTable =  $('#dataTable').DataTable( {
        serverSide: true,
        bProcessing: true,
        ajax:{
            url :"../datos/datos.diferidos_corte_reco.php",
            type: "post",
            data:{proyecto:proyecto,
                fecini:fecini,
                fecfin:fecfin, tip:'genReporte',
            },

            error: function(){  // error handling

            }
        },
        dom: "lfrtip",
        deferRender: true,

        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        language:  {url: '../../js/DataTables-1.10.15/Spanish.json'},

    } );
    $("#divReporte").empty();
    $("#divReporte").append('<button id="btnExportarExcel" onclick="exportarReporteExcel()" class="btn-info">Exportar a Excel</button>');


    swal.close();

}


function exportarReporteExcel(){

    var datos=$("#dif_cortereco").serializeArray();
    datos.push({name:"tip",value:'exportarTabla'});
    swal
    ({
            title: "Aviso!",
            text: "El reporte puede demorar unos minutos dependiendo de la cantidad de regitros a exportar.\n¿Desea exportarlo a Excel?",
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
    $("#tablaExportar").empty(datos);
    $.ajax({
        type:"POST",
        url: "../datos/datos.diferidos_corte_reco.php",
        data:datos,
        start_time: new Date().getTime(),
        success:function(res){

        },
        error:function(jsxhr,exception){
            mensajeCustom('Contacte a sistemas. '+exception,'error','error');
        }
    });
            }

        });
}

function mensajeCustom(msj='',titulo='',icono='') {

    swal(titulo, msj, icono)

}
