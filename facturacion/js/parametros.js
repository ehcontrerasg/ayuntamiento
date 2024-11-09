/**
 * Created by Tecnologia on 5/7/2018.
 */
$(document).ready(function(){
    /*desContr();*/
    compSession();
    compSession(getReport);


    $("#frmEditarParametro").submit(
        function(){
            compSession(guardarCambios);
        }
    )


    $("#dataTable").hide();

    /*getParametroSeleccionado();*/

   // $("#btnGuardarCambios").on("click",guardarCambios);
   // $("#frmEditarParametro").submit(guardarCambios);

});

function getReport()
{
    $.post("../datos/datos.parametros.php",{peticion:"reporte"},function(res){
        /*var dat = JSON.parse(res);*/
        if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
            $('#dataTable').DataTable().destroy();
        }
         table=$('#dataTable').DataTable( {
            data: JSON.parse(res),
             dom: 'Bfrtip',
             buttons: [
                 { extend: 'copy', text:' Copiar',  className: 'btn btn-primary glyphicon glyphicon-duplicate' },
                 { extend: 'csv',  text:' CVS', className: 'btn btn-primary glyphicon glyphicon-save-file' },
                 { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt' },
                 { extend: 'pdf',  text:' PDF',  className: 'btn btn-primary glyphicon glyphicon-file' },
                 {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' }
             ],
            columns: [
                { title: "Código" },
                { title: "Parámetro" },
                { title: "Valor de parámetro" },
                { title: "Descripción de parámetro" },
                {defaultContent: "<button type='button' class='editar btn btn-primary glyphicon glyphicon-edit'' data-toggle='modal' data-target='#frmAdministrarParametros'> Editar</button>"},

            ],



             language:
                 {
                     "url":"//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                 },

            "info":     false,
            "order": [[ 2, "desc" ]],
            "paging" : true,

        });
        getParametroSeleccionado("#dataTable tbody",table);
        $('#dataTable').show();

    });


}

var idParametro;
var getParametroSeleccionado= function (tbody,table)
{
    $(tbody).on("click","button.editar",function(){
        var data = table.row($(this).parents("tr")).data();

        $("#txtNombreParametro").val(data[1]);
        $("#txtValorParametro").val(data[2]);

        idParametro=data[0];




    });

}

function guardarCambios()
{

    var valParametro=$("#txtValorParametro").val();
    /*alert("Val parámetro"+valParametro);*/
    $.post("../datos/datos.parametros.php",{id:idParametro,val:valParametro,peticion:"actualiza"},function(res){
       /* alert(res);*/
        $("#frmAdministrarParametros").modal("hide");
        getReport()
    });
    /*console.log(idParametro);*/
    //console.log(valParametro);

}

function checkStatus(){

    $.get('../webServices/ws.getSession.php', function(respuesta) {

        var resp = JSON.parse(respuesta);
        if (typeof(resp.usuario) === "undefined") {
            $('#myModal').modal('show', function() {
                $('#main').html(' ');
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