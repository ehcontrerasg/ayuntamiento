$(document).ready(function(){
    desContr();
    compSession();
    compSession(llenarSpinerPro);

    $("#repdeuAcuInm").submit(
        function(){
            compSession(generaRep);
        }
    )

});


function generaRep()
{
    var datos=$("#repdeuAcuInm").serializeArray();
    var codInmuble=$("#codInmuble").val();
    var proyecto=$("#selPro").val();
    $.ajax
    ({

        url : '../datos/datos.inmDeudaAcumulada.php',
        type : 'POST',
        dataType : 'json',
        data : { tipo : 'report',codInmuble:codInmuble,proyecto:proyecto },
        success : function(res) {

            if ($.fn.dataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            $('#dataTable').DataTable({
                data: res,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', text:' Copiar', className: 'btn btn-primary glyphicon glyphicon-duplicate' },
                    { extend: 'csv',  text:' CVS', className: 'btn btn-primary glyphicon glyphicon-save-file' },
                    { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt' },
                    { extend: 'pdf',  text:' PDF',  className: 'btn btn-primary glyphicon glyphicon-file' },
                    {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' }
                ],
                columns: [

                    {title: "FACTURA"},
                    {title: "PERIODO"},
                    {title: "TOTAL FACTURA"},
                    {title: "TOTAL PAGADO"},
                    {title: "TOTAL CREDITO"},
                    {title: "TOTAL DEBITO"},
                    {title: "TOTAL DEUDA PERIODO"},
                    {title: "TOTAL DEUDA ACUMULADA"},

                ],
                "info": false,
                "paging": false,
                "order": false,
                "scrollY": '300px',
                "scrollCollapse": true,

                    "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}


            });


            $('#dataTable').show();


        },

        error : function(xhr, status) {

        }

    });

}


function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.inmDeudaAcumulada.php',
        type : 'POST',
        dataType : 'json',
        data : { tipo : 'selPro' },
        success : function(json) {
          //  $('#selPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selPro').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
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