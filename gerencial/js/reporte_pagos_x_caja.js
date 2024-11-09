$(document).ready(function() {
    desContr();
    compSession();
    compSession(llenarSpinerPro);


   $("#actCampos").submit(function(){

        compSession(actCampos);
    });


    $("#proyecto").change(
        function(){
            cargEntidades();
        }
    );

    $("#entidad").change(
        function(){
            cargPuntos();
        }
    );


    $('#repPagos').submit(
        function(){
            compSession(getReport);
        }
    )





});


function cargEntidades()
{

    $.ajax
    ({
        url : '../datos/datos.reporte_pagos_x_caja.php',
        type : 'POST',
        dataType : 'json',
        data : { tipo : 'selEnt' , proyecto:$('#proyecto').val() },
        success : function(json) {
            $('#entidad').empty();
            $('#entidad').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#entidad').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function cargPuntos()
{


    $.ajax
    ({
        url : '../datos/datos.reporte_pagos_x_caja.php',
        type : 'POST',
        dataType : 'json',
        data : { tipo : 'selPunt' , entidad:$('#entidad').val() },
        success : function(json) {
            $('#punto').empty();
            $('#punto').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#punto').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.reporte_pagos_x_caja.php',
        type : 'POST',
        dataType : 'json',
        data : { tipo : 'selPro' },
        success : function(json) {
            $('#proyecto').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#proyecto').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function getReport()
{
    var datos=$("#repPagos").serializeArray();
    datos.push({name: 'tipo', value: 'report'});
    $.ajax
    ({
            url : '../datos/datos.reporte_pagos_x_caja.php',
            type : 'POST',
            dataType : 'json',
            data : datos,
        success : function(res) {

             if ($.fn.dataTable.isDataTable('#dataTable')) {
                 $('#dataTable').DataTable().destroy();
             }

            table = $('#dataTable').DataTable({
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
                    {title: "INMUEBLE"},
                    {title: "FECHA PAGO"},
                    {title: "FECHA INGRESO"},
                    {title: "IMPORTE"},
                    {title: "APLICADO"},
                    {title: "CAJA"},
                    {title: "PUNTO"},
                    {title: "ENTIDAD"},
                    {title: "TIPO"},
                    {title: "SUMINISTRO"}
                ],
                 "info": false,
                 "paging": true,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}


             });

            obtener_data_editar("#dataTable tbody", table);
            $('#dataTable').show();

            $('#dataTable').DataTable( {
                "order": [[ 1, "asc" ]]
            } );



        },

                 error : function(xhr, status) {

            }

        });

}


var nfc;
var limite;
var concecutivo;
var proyecto;




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
                $("#ingCodDoc").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password'  placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        animation: "slide-from-top"

                    },
                    function(inputValue){
                        if (inputValue === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{
                            $("#ingCodDoc").focus();
                            iniSes();
                        }
                    }
                );


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
