$(document).ready(function(){
   // desContr();
    compSession();
    compSession(llenarSpinerMotivo());
    compSession(getReport);
compSession(llenarAtendida());


    $("#repPagDetCon").submit(
        function(){
            swal
            ({
                    title: "Advertencia!",
                    text: "Desea aplicar estos filtros?.",
                    showConfirmButton: true,
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {

                        compSession(getReport);
                    }
                });

        }


    )


});

function getReport()
{


    var motivo =$("#idMotivo").val();
    var concepto  = 20;
    var fechaIn = $("#idIniFec").val();
    var fechaFn = $("#idFinFec").val();



            if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                $('#dataTable').DataTable().destroy();
            }
            var tbody="#dataTable tbody";

           table= $('#dataTable').DataTable( {
               "processing": "true",
               "targets": 'no-sort',
               "bSort": false,
               "order": [],
               "ajax" : {
                   "method" : "POST",
                   "data":{ tip : 'report',motivo: motivo,concepto : concepto,fechaIn : fechaIn, fechaFn : fechaFn },
                   "url" : "../datos/datos.averias_recibidas.php",
               },
              // data: res,
                dom: 'lfrtip',
                "columns": [
                    {"data": "CODIGO"},
                    {"data": "OBSERVACION"},
                    {"data": "FECHA"},
                    {"data": "NOMBRE"},
                    {"data": "TELEFONO"},
                    {"data": "DIRECCION"},
                    {"data": "EMAIL"},
                    {"data": "LATITUD"},
                    {"data": "LONGITUD"},
                    {"data": "DESCRIPCION"},
                    {"data": "ID"},
                    {"data": "ESTADO"},

                    {"defaultContent": "<button type='button'  id='mostrarDetalles'  class='btn btn-primary glyphicon glyphicon-eye-open'> Detalles</button>"}

                ],

                "columnDefs": [
                    {
                        "targets": [ 3 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 4 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 5 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 6 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 7 ],
                        "visible": false,
                        "searchable": false
                    },

                    {
                        "targets": [ 8 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 10],
                        "visible": false,
                        "searchable": false
                    },

            {
                        "targets": [ 6 ],
                        "visible": false,
                        "searchable": false
                    }
                ],

                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "info": false,
                "paging": true,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}

            });

            $('#'+'dataTable tbody').on('click','#mostrarDetalles', function () {
                var data_row = table.row($(this).closest('tr')).data();
                var idReclamo = data_row.ID;
                mostrar(idReclamo)

            });


            $('#dataTable').show();


    swal.close();

}







function mostrar(idReclamo){


    popup(idReclamo);
}


var popped = null;
function popup(id) {
    var params;
    var uri="vista.detalles_averia.php?id="+id;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        }
        else {
            params = "toolbar=no,width=" + screen.width + ",height="+ screen.height +",directories=no,status=no,scrollbars="  + ",menubar=no,resizable=no,location=no,top=0,left=0,fullscreen=yes";
            popped = window.open(uri, "popup", params);
        }
    }
}

function llenarSpinerMotivo()
{

    $.ajax
    ({
        url : '../datos/datos.averias_recibidas.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selMot' },
        success : function(json) {
            $('#idMotivo').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#idMotivo').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
function llenarAtendida()
{

    $.ajax
    ({
        url : '../datos/datos.averias_recibidas.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selAten' },
        success : function(json) {
            $('#Atendida').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#Atendida').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

            }
        },
        error : function(xhr, status) {

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




