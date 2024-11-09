$(document).ready(function() {
   // desContr();
    compSession();

compSession(getUsuariosCajas);




});

function getUsuariosCajas()
{
var data="#dataTable tbody";
    $.ajax
    ({
        url : '../datos/datos.habilitar_deshabilitar_usuarios.php',
        type : 'POST',
        dataType : 'json',
        data : { tipo : 'dtUsrCajas' },
        success : function(res) {

            if ($.fn.dataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            table = $('#dataTable').DataTable({

                data: res,



                columns: [
                    {title: "Nombre"},
                    {title: "Apellido"},
                    {title: "Cedula"},
                    {title: "Estado"},

                    {"defaultContent": '<select id="selEstado" onchange="runActEstado(this.value)" name="selEstado" class="form-control" >' +
                        '<option value="" selected>Seleccione una acción</option>'+
                        '<option  value="Habilitar">Habilitar</option>'+
                        '<option value="Deshabilitar">Deshabilitar</option>'+
                        '</select>'},
                ],

                "scrollY":  "350px",
                "scrollCollapse": true,
                "paging":         false,
                "order": false,

                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}


            });


         obtener_data_editar("#dataTable tbody", table);



            $('#dataTable').show();


        },

        error : function(xhr, status) {

        }

    });

}





function runActEstado(valorSelect)
{

    var idUsuario=$('#usuario').val();
    var estadoUsuario=$('#estado').val();
    var nombre=$('#nombre').val();

if (estadoUsuario==='Habilitada')
    estadoUsuario='Habilitar';
    else
        estadoUsuario='Deshabilitar';

    if (valorSelect==estadoUsuario)
    {
        swal
        ({
            title: "Aviso!",
            text: "" + nombre + " ya esta " + $('#estado').val() + ".",
            showConfirmButton: true
        },
            function (isConfirm) {
                if (isConfirm) {
                   location.reload()
                }
            });

    } else {


        swal
        ({
                title: "Advertencia!",
                text: "Desea cambiar el estado de " + nombre + "?.",
                showConfirmButton: true,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    actEstado(idUsuario, valorSelect);
                }
                else
                    location.reload();
            });
    }
}



function obtener_data_editar (tbody, table) {

        $(tbody).on("click", "#selEstado", function () {

            var data = table.row($(this).parents("tr")).data();

            var nombre = data[0]+" "+data[1];
            var idUsuario = data[2];
            var estadoUsuario = data[3];
            $('#usuario').val(idUsuario);
            $('#estado').val(estadoUsuario);
            $('#nombre').val(nombre);



        });
    }


function actEstado(idUsuario,estado){



//alert("selecionado: "+estado+" usuario: "+idUsuario);
    $.ajax
    ({
        url: '../datos/datos.habilitar_deshabilitar_usuarios.php',
        type: 'POST',
        dataType: 'text',
        data: {estado: estado, idusuario: idUsuario, tipo: 'actualiza'},
        success: function () {
            swal
            ({
                    title: "Aviso",
                    text: "Estado Actualizado con exito!",
                    showConfirmButton: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.reload()
                    }
                });

        },
        error: function (xhr, status) {
            swal
            ({
                    title: "Aviso!",
                    text: "Hubo un problema contacte a sistemas, status: "+status,
                    showConfirmButton: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.reload()
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
