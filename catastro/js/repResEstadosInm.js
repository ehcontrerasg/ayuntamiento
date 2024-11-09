$(document).ready(function(){

    desContr();
    compSession();
    compSession(llenarSpinerEst);
    compSession(llenarSpinerPro);
    compSession(llenarSpinerPer);

    var form = $('form[name="report_form"]');
    $('#dataTable').hide();
    $(form).submit(getReport);


    function getReport()
    {

        var datos = $(form).serialize();
        $.get('../datos/datos.resumenEstadosInm.php?tipo=report&'+datos, function(res) {
            var dat = JSON.parse(res);

            if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                $('#dataTable').DataTable().destroy();
            }

            $('#dataTable').DataTable( {
                data: JSON.parse(res),

                columns: [{ title: "Uso" }, { title: "Inmuebles Este" }, { title: "Inmuebles Norte" }, { title: "Total" },{title:"Unidades Este"},{title:"Unidades Norte"}, {title:"Total"}


                   /* { title: "sumaN" },
                    { title: "sumaE" },*/

                ],


                "info":     false,

                "paging" : true,

                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "Ningún dato disponible en esta tabla",
                    "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":   "",
                    "sSearch":        "Buscar:",
                    "sUrl":           "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":    "Último",
                        "sNext":    "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }


            });
            $('#dataTable').show();

            /*  $('.reportId').click(function(event) {
                  event.preventDefault();
                  document.getElementById('reportPDF').src = '';
                  var id = $(this).attr('id');
                  document.getElementById('reportPDF').src = '../Datos/reportPDF.php?id='+id;
              });*/
        });

    }


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
                $("#rutPdcSelPro").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password' tabindex='4' placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        inputPlaceholder: "Usuario",
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
                            $("#rutPdcSelPro").focus();
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

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.resumenEstadosInm.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selPro').empty();
            $('#selPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {

                $('#selPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {


        }
    });
}

function llenarSpinerEst()
{

    $.ajax
    ({
        url : '../datos/datos.resumenEstadosInm.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEst' },
        success : function(json) {
            $('#selEstado').empty();
            $('#selEstado').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {

                $('#selEstado').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

            }
        },
        error : function(xhr, status) {


        }
    });
}


function llenarSpinerPer()
{

    $.ajax
    ({
        url : '../datos/datos.resumenEstadosInm.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPer' },
        success : function(json) {
            $('#selPeriodo').empty();
            $('#selPeriodo').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {

                $('#selPeriodo').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

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