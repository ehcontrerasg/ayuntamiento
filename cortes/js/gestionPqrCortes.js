
function cerrarModal(){
    event.preventDefault();
    jQuery.noConflict();
    $(this).data('bs.modal', null);
    $('#modalAsignacionCorte').modal('hide');


}
function asignar(elemento){

    event.preventDefault();
    jQuery.noConflict();
    $('#modalAsignacionCorte').modal('show');

    var elemento_id=$(elemento).parents()[2].getAttribute("id");
    var codigo_pqr = elemento_id.substr(3,elemento_id.length);

    $("#lblCodigoPqr").text(codigo_pqr);

    cargarDatos(codigo_pqr);
    corteAsignado(codigo_pqr);

    $("#btnEliminarAsignacion").click(function(){

        swal
        ({
                title: "Advertencia!",
                text: "¿Desea eliminar asignación?",
                showConfirmButton: true,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                closeOnCancel: true,
                confirmButtonText: "Sí",
                cancelButtonText: "No"
            },
            function(isConfirm)
            {
                if (isConfirm)
                {
                    eliminarAsignacion(codigo_pqr);
                }
            });
    });
}

function asignarCorte() {
    if(respuesta=="false"){
        actualizarAsignacionCorte()
    }else{
        asignarOperario();
    }
}

//Función que carga los datos de la cierta solicitud
function cargarDatos(cod_pqr){
    $.ajax({
        type:"POST",
        url:"../datos/datos.gestion_pqr.php",
        data:{tip:"getDatos",cod_pqr:cod_pqr},
        datatype:"json",
        success:function(res){
            $("#lblInmueble").empty();
            $("#lblDireccion").empty();
            $("#lblZona").empty();

            var datos = JSON.parse(res);
            $("#lblInmueble").append(datos[0][0]);
            $("#lblDireccion").append(datos[0][1]);
            $("#lblZona").append(datos[0][2]);

            var proyecto= datos[0][3];
            cargarOperarios(proyecto);
        },
        error:function(xhjs,exception){
            console.log(xhjs+" "+exception);
        }
    });
}

function cargarOperarios(proyecto){
    $.ajax({
        type:"POST",
        data:{tip:"getOperarios",proyecto:proyecto},
        url:"../datos/datos.gestion_pqr.php",
        datatype:"json",
        success:function(res){
            var datos= JSON.parse(res);
            for(var indice=0;indice<datos.length;indice++) {
                $("#slcOperario").append(
                    "<option value='" + datos[indice][0] + "'>" + datos[indice][1] + "</option>"
                )
            }
        },
        error:function(xhjs,exception){
            console.log(xhjs+" "+exception);
        }
    });
}

function asignarOperario(){

    var codigo_pqr=  $("#lblCodigoPqr").text();
    var operario = $("#slcOperario").val();
    var cod_inmueble = $("#lblInmueble").text();

    $.ajax({
        type:"POST",
        url:"../datos/datos.gestion_pqr.php",
        data:/*datos*/{tip:"asignarOperario",operario:operario,cod_pqr:codigo_pqr,cod_inmueble:cod_inmueble},
        success:function(res){
            if(res=="true"){
                swal( {
                    title: "Mensaje!",
                    text: "Corte asignado correctamente.",
                    type:"success",
                    showConfirmButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: true},function(confirm) {
                    if (confirm){

                        $("#btnCerrarModal").click();
                       // $("#modalAsignacionCorte").data('modal', null);

                    }
                });

            }else{
                swal("Mensaje!", "Error asignando corte. Cantacte a sistemas.", "error");
            }
        },
        error:function(xhjs,exception){
            console.log(xhjs+" "+exception);
        }
    });
}

//Función que verifica si cierto corte ha sido asignado.
function corteAsignado(codigo_pqr){
    $.ajax({
        type:"POST",
        url:"../datos/datos.gestion_pqr.php",
        data:{tip:"corteAsignado",cod_pqr:codigo_pqr},
        async:false,
        success:function(res){

            var operario= JSON.parse(res);
            if(operario==""){
                $("#avisoAsignacionCorte").css('display','none');
                $("#btnEliminarAsignacion").css('display','none');

                callback("true");
            }else{
                $("#avisoAsignacionCorte").empty();
                $("#avisoAsignacionCorte").append("NOTA: ESTA SOLICITUD TIENE A "+operario+" COMO OPERARIO ASIGNADO.");
                $("#avisoAsignacionCorte").css('display','block');
                $("#btnEliminarAsignacion").css('display','inline-block');

                callback("false");
            }
        },
        error:function(rhjs,exception){
            console.log(rhjs+" "+exception);
        }
    });
}

var respuesta=""; //Variable que almacena si ya un operario está asignado a cierto corte.
//Función que almacena la respuesta de la función 'corteAsignado'
function callback(res){
    respuesta=res;
}

function actualizarAsignacionCorte(){
    var codigo_pqr=  $("#lblCodigoPqr").text();
    var operario = $("#slcOperario").val();
    $.ajax({
        type:"POST",
        url:"../datos/datos.gestion_pqr.php",
        data:/*datos*/{tip:"actualizarAsignacionOperario",operario:operario,cod_pqr:codigo_pqr},
        success:function(res){
            if(res=="true"){
                swal( {title: "Mensaje!",
                    text: "Corte reasignado correctamente.",
                    type:"success",
                    showConfirmButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: true

                },function(confirm) {
                    if (confirm){
                        $(this).removeData('modalAsignacionCorte');
                        $("#btnCerrarModal").click();
                    }
                });
            }else{
                swal("Mensaje!", "Error reasignando corte. Cantacte a sistemas.", "error");
            }
        },
        error:function(xhjs,exception){
            console.log(xhjs+" "+exception);
        }
    });
}

function eliminarAsignacion(cod_pqr){
    $.ajax({
        type:"post",
        url: "../datos/datos.gestion_pqr.php",
        data: {tip:"eliminarAsignacion", cod_pqr:cod_pqr},
        success: function(res){

            if(res=="true"){
                swal( {title: "Mensaje!",
                    text: "Asignación eliminada correctamente.",
                    type:"success",
                    showConfirmButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: true
                },function(confirm) {
                    if (confirm){
                        $("#btnCerrarModal").click();
                    }
                });
            }},
        error:function(jqxhr,exception){
            console.log(jqxhr+" "+exception);
        }

    });
}

