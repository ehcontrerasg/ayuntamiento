var xid=0;
//compSession(cargapag);
$(document).ready(function(){

    desContr();
    compSession();
    compSession(cargapag);

    $('#asiOrdCorrSelPro').change(
        function(){
            compSession(llenarSpinerSec);
        }
    );

    $("#asiOrdCorrForm").submit(
        function(){
            compSession(getOrdenes);
        }
    );

    $('#asiOrdCorrRutForm').submit(
        function(){
            compSession(asignaRutas);
        }
    )



});



function asignaRutas(){
    for(i=0;i<xid;i++){
        if($("#usu"+i).val().trim()!=''){
            if(asignador($("#usu"+i).val(),$("#ruta"+i).text())){
                $("#spanMensaje"+i).html("Usuario asignado correctamente.")
                    .css("color","green")
                ;
            }
        }


    }
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


function cargapag() {
    llenarSpinerPro();
    varificaPerf();

}

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.asigna_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#asiOrdCorrSelPro').empty();
            $('#asiOrdCorrSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiOrdCorrSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSpinerSec()
{

    $.ajax
    ({
        url : '../datos/datos.asigna_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selSec' ,pro:$('#asiOrdCorrSelPro').val()},
        success : function(json) {
            $('#asiOrdCorrSelSec').empty();
            $('#asiOrdCorrSelSec').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiOrdCorrSelSec').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}



function llenarSpinerUsu(selUsr,idusr)
{

    $.ajax
    ({
        url : '../datos/datos.asigna_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'SelUsu',pro:$('#asiOrdCorrSelPro').val()},
        success : function(json) {
            selUsr.empty();
            selUsr.append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                if(idusr==json[x]["CODIGO"]){
                    selUsr.append(new Option(json[x]["NOMBRE"], json[x]["CODIGO"], true, true));
                }else{
                    selUsr.append(new Option(json[x]["NOMBRE"], json[x]["CODIGO"], false, false));
                }

            }
        },
        error : function(xhr, status) {

        }
    });
}



function getOrdenes()
{
    $.ajax
    ({
        url : '../datos/datos.asigna_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selRutAsig' ,sec:$('#asiOrdCorrSelSec').val()},
        success : function(json) {

            $('#asiOrdCorrRutForm').empty();

            var divTitulo =$('<div/>', {
                'class' : 'subCabecera',
                'text':'Asignacion y Reasignacion de ordenes'
            });

            $('#asiOrdCorrRutForm').append(divTitulo);

            for(var x=0;x<json.length;x++)
            {
                var spanExt =$('<span/>', {
                    'class' : 'datoForm col1'
                });

                var spanInt =$('<span/>', {
                    'class' : 'titDato numCont2',
                    'text':json[x]["RUTA"],
                    'id':'ruta'+x
                });


                spanExt.append(spanInt);

                var spanInt =$('<span/>', {
                    'class' : 'titDato numCont2',
                    'text':json[x]["CANTIDAD"]
                });

                spanExt.append(spanInt);

                var spanInt =$('<span/>', {
                    'class' : 'inpDatp numCont2'
                });

                var sel1=$('<select/>', {
                    'id':'usu'+x

                });

                var spanMensaje =$('<span/>',{
                    'id':'spanMensaje'+x
                });

                spanInt.append(sel1);
                spanExt.append(spanInt);
                spanExt.append(spanMensaje);
                llenarSpinerUsu(sel1,json[x]["ID_USUARIO"])

                $('#asiOrdCorrRutForm').append(spanExt);
            }
            xid=x;
            var spanExt =$('<span/>', {
                'class' : 'datoForm col1'
            });

            var inp=$('<input/>', {
                'id':'asiOrdCorrButAsig',
                'value':'Asignar',
                'class':'botonFormulario botFormMed',
                'type':'submit'

            });
            spanExt.append(inp);
            $('#asiOrdCorrRutForm').append(spanExt);




        },
        error : function(xhr, status) {

        }
    });
}





function iniSes(){
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data : { tip : 'iniSes',pas:$("#pass").val(),usu:$("#usr").val()},
        dataType : 'text',
        success : function(json) {
            if (json=="true"){
                swal("Loggin Exitoso!")
             //   compSession(cargapag);
            }else if(json=="false"){
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        inputPlaceholder: "Write something",
                        text: "Usuario o Contraseña  incorrecta.<br>" +
                        " <input type='text' class='estilo-inp' placeholder='Usuario' id='usr'><br> <input  placeholder='Password' tabindex='4' class='estilo-inp' type='password' id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },
                    function(i){
                        if (i === false)
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
                    });
            }
        },
        error : function(xhr, status) {
            alert("error");
            return false;
        }
    });

}

function asignador(usu,rut){

    var respuesta = false;
    $.ajax
    ({
        url : '../datos/datos.asigna_orden_cor.php',
        type : 'POST',
        data : {usu:usu,tip:'asig',rut:rut},
        dataType : 'json',
        async: false,
        success : function(json) {
            if (json["res"]=="true"){
                respuesta=  true;
            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
                respuesta = false;
            }
        },
        error : function(xhr, status) {

            respuesta = false;
        }
    });

     return respuesta;
}

function varificaPerf() {
    var urlNmae=window.location.pathname.split("/")[window.location.pathname.split("/").length-1 ] ;
    $.ajax
    ({
        url : '../datos/datos.asigna_orden_cor.php',
        type : 'POST',
        data : {tip:'compPerf',rut:urlNmae},
        dataType : 'json',
        success : function(json) {
            if (json[0]["CANTIDAD"]==1){
            }else if(json[0]["CANTIDAD"]==0){
                $("#bod").empty();
                $("#bod").text("acceso restringido");

            }
        },
        error : function(xhr, status) {

            return false;
        }
    });


}






