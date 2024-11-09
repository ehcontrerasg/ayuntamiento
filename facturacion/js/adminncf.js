$(document).ready(function() {
    desContr();
    compSession();
    compSession(getReport);

   $("#actCampos").submit(function(){

        compSession(actCampos);
    });


});

function getReport()
{

    $.ajax
    ({
            url : '../datos/datos.adminncf.php',
            type : 'POST',
            dataType : 'json',
            data : { tipo : 'report' },
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
                    {title: "ID NCF"},
                    {title: "PROYECTO"},
                    {title: "CONSECUTIVO"},
                    {title: "LIMITE"},
                    {title: "DESCRIPCION"},
                    {title: "RESTANTES"},
                    {"defaultContent": "<button type='button' id='editar' data-toggle='modal' data-target='#miModal' class='btn btn-primary glyphicon glyphicon-edit'> Editar</button>"}
                ],
                 "info": false,
                 "paging": true,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}


             });

            obtener_data_editar("#dataTable tbody", table);
            $('#dataTable').show();


        },

                 error : function(xhr, status) {

            }

        });

}


var nfc;
var limite;
var concecutivo;
var proyecto;


var obtener_data_editar = function(tbody, table) {

    $(tbody).on("click","#editar", function(){
        var data = table.row($(this).parents("tr") ).data();
        //console.log(data.FECHA_DOCUMENTO);
         $("#nfc").val(data[0]);
         $("#limite").val(data[3]);
         $("#consecutivo").val(data[2]);
         $("#proyecto").val(data[4]);
        nfc=data[0];
        proyecto= data[1];


    });


}

function actCampos(){

    limite= $("#limite").val();
    concecutivo=   $("#consecutivo").val();


    $.ajax
    ({
        url : '../datos/datos.adminncf.php',
        type : 'POST',
        dataType : 'text',
        data : {nfc:nfc ,limite:limite,proyecto: proyecto,consecutivo: concecutivo,tipo: 'actualiza'},
        success : function() {

            $('#miModal').modal('hide');
            getReport();


        },
        error : function(xhr, status) {

        }
    });

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
