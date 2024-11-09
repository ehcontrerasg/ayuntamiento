$(document).ready(function() {
    desContr();
    compSession();

   $("#buscar").click(function(){

        compSession(getReport);
    });


});

function getReport()
{

    var parametros=[];


    parametros.push({name: 'tipo', value: 'report'});
    parametros.push({name: 'inmueble', value: $("#codsistema").val()});

    $.ajax
    ({
            url : '../datos/datos.anulaNotas.php',
            type : 'POST',
            dataType : 'json',
            data : parametros,
        success : function(res) {

             if ($.fn.dataTable.isDataTable('#dataTable')) {
                 $('#dataTable').DataTable().destroy();
             }

            table = $('#dataTable').DataTable({
                data: res,
                dom: 'Bfrtip',
                columns: [
                    {title: "ID NOTA"},
                    {title: "FACTURA"},
                    {title: "TIPO NOTA"},
                    {title: "TOTAL NOTA"},
                    {title: "TOTAL FACTURA"},
                    {"defaultContent": "<button type='button' id='eliminaNota' data-toggle='modal' data-target='#miModal' class='btn btn-primary glyphicon glyphicon-edit'> Elimina Nota</button>"}
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

    $(tbody).on("click","#eliminaNota", function(){
        var data = table.row($(this).parents("tr") ).data();
        //console.log(data.FECHA_DOCUMENTO);
        anulaNotas(data[0]);



    });


}

function anulaNotas(idnota){




    /*$.ajax
    ({
        url : '../datos/datos.anulaNotas.php',
        type : 'POST',
        dataType : 'text',
        data : {idnota:idnota ,tipo: 'elimina'},
        success : function() {

            $('#miModal').modal('hide');
            getReport();


        },
        error : function(xhr, status) {

        }
    });*/

    swal
    ({
            title: "Aviso!",
            text: "Desea Anular la nota numero "+idnota,
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
            $.ajax
                ({
                    url : '../datos/datos.anulaNotas.php',
                    type : 'POST',
                    dataType : 'text',
                    data : {idnota:idnota ,tipo: 'elimina'},
                    success : function() {
                        swal
                        (
                            {
                                title: "Mensaje!",
                                text: "Ha anulado la nota con exito.",
                                showConfirmButton: true
                            },
                            function(isConfirm)
                            {
                                if (isConfirm)
                                {
                                    getReport();
                                }
                            }
                        );

                        getReport();


                    },
                    error : function(xhr, status) {

                    }
                });
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
