
var factura="";
$("#btnBuscarFactura").click(function(){

    compSession(buscarFactura);

});

$("#btnValidar").click(function(){
   // validarDatos();
    compSession(validarDatos);
});

$("#txtFactura").change(function(e){
    verificaCampoVacio(e.target)
});

function buscarFactura(){
    factura=$("#txtFactura").val();
    $("#divRepImpFac").attr("data","../../facturacion/clases/classFacturaPdf2.php?factura="+factura);
    $.post("../datos/datos.editarFactura.php",{tip:"obtenerDatosFactura",factura:factura},function(res){

        if(res.length>0){
            cargarTipoDocumento();
            $("#numero_documento").val(res[0][1]);
            $("#nombre_usuario").val(res[0][2]);
            $("#ncf").val(res[0][3]);
            $("#titulo_comprobante").val(res[0][4]);
            //let partes =  res[0][5].split("/");
            //let nuevaFecha = partes[2]+"-"+partes[1]+"-"+partes[0]
            //$("#dtFechaVencimientoNCF").val(nuevaFecha);
            $("#datosFactura").css("display","block")
        }else{

            swal("Error!", "Debe introducir una factura válida.", "error");
            $("#datosFactura").css("display","none")
        }

    },'json');

}
function validarDatos(){
    //console.log("ENTRÓ A VALIDAR");
    var tipo_doc= $("#tipoDoc option:selected").text();
    var numero_doc= $("input[name='numero_documento']").val();
    var nombre_usuario= $("input[name='nombre_usuario']").val();
    var ncf= $("input[name='ncf']").val();
    var titulo_comprobante= $("input[name='titulo_comprobante']").val();
    var fechaVencimientoNcf = $("#dtFechaVencimientoNCF").val();
    
     $("#divRepImpFac").attr("data","../../facturacion/clases/classFacturaPdfEditable.php?factura="+factura+"&tipo_doc="+tipo_doc+"&numero_doc="+numero_doc+"&nom_user="+nombre_usuario+"&ncf="+ncf+"&msj_ncf="+titulo_comprobante+"&vencimiento_ncf="+fechaVencimientoNcf);
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
                $("#ingResInsInpCodSis").focus(false);
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
                            $("#ingResInsInpCodSis").focus();
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
//Función que detecta cuando el campo de busqueda de factura está vacío
function verificaCampoVacio(campo){

    if(campo.value==""){
        $("#datosFactura").css("display","none");
    }
}

function cargarTipoDocumento(){

    $.post("../datos/datos.editarFactura.php",{tip:"getTiposDocumento"},function(json){
        $('#tipoDoc').empty();
        $('#tipoDoc').append(new Option('', '', true, true));
        for(var x=0;x<json.length;x++)
        {

            $('#tipoDoc').append(new Option(json[x]["DESCRIPCION_TIPO_DOC"], json[x]["ID_TIPO_DOC"], false, false));
        }

    },'json');
}
