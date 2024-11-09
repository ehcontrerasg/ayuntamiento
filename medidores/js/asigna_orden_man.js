var xid=0;
$(document).ready(function(){

    desContr();
    compSession();

    compSession(llenarSpinerPro);

    $('#asiOrdManSelPro').change(
        function(){
            compSession(llenarSpinerSec);
        }
    )

    $("#asiOrdManForm").submit(
        function(){
            compSession(getOrdenes);
        }
    )

    $('#asiOrdManRutForm').submit(
        function(){
            compSession(asignaRutas);
        }
    )



});



function asignaRutas(){
    for(i=0;i<xid;i++){
        if($("#usu"+i).val().trim()!=''){
            asignador($("#usu"+i).val(),$("#ruta"+i).text());
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

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.asigna_orden_man.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#asiOrdManSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiOrdManSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
        url : '../datos/datos.asigna_orden_man.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selSec' ,pro:$('#asiOrdManSelPro').val()},
        success : function(json) {
            $('#asiOrdManSelSec').empty();
            $('#asiOrdManSelSec').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiOrdManSelSec').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
        url : '../datos/datos.asigna_orden_man.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'SelUsu',pro:$('#asiOrdManSelPro').val()},
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
        url : '../datos/datos.asigna_orden_man.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selRutAsig' ,sec:$('#asiOrdManSelSec').val()},
        success : function(json) {

            $('#asiOrdManRutForm').empty();

            var divTitulo =$('<div/>', {
                'class' : 'subCabecera',
                'text':'Asignacion y Reasignacion de ordenes'
            });

            $('#asiOrdManRutForm').append(divTitulo);




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
                spanInt.append(sel1);
                spanExt.append(spanInt);
                llenarSpinerUsu(sel1,json[x]["ID_USUARIO"])



                $('#asiOrdManRutForm').append(spanExt);
            }
            xid=x;
            var spanExt =$('<span/>', {
                'class' : 'datoForm col1'
            });

            var inp=$('<input/>', {
                'id':'asiOrdManButAsig',
                'value':'Asignar',
                'class':'botonFormulario',
                'type':'submit'

            });
            spanExt.append(inp);
            $('#asiOrdManRutForm').append(spanExt);




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
                compSession(llenarSpinerPro);
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

    $.ajax
    ({
        url : '../datos/datos.asigna_orden_man.php',
        type : 'POST',
        data : {usu:usu,tip:'asig',rut:rut},
        dataType : 'json',
        success : function(json) {
            if (json["res"]=="true"){

            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
            }
        },
        error : function(xhr, status) {

            return false;
        }
    });

}








