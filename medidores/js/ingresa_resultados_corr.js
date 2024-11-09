var listaAct;
$(document).ready(function(){

    listaAct = new Array();
    desContr();
    compSession();
    compSession(llenarSelMed);
    compSession(llenarSelCal);
    compSession(llenarSelEmp);

    $("#ingResManInpCodSis").blur(
        function(){
            if($("#ingResManInpCodSis").val()!=''){
                compSession(completaDatos);
            }else{
                $("#ingResManInpCodSis").focus();
                $('#ingResManCorForm')[0].reset();
            }

        }
    )


    $("#ingResManCorButAgrAct").dblclick(
        function(){popup("vista.lista_corr_act.php",750,400,'yes');

        }
    )




    $("#ingResManCorForm").submit(


        function(){
            compSession(guardaOrden);
        }
    )



});

function guardaOrden(){

    var datos=$("#ingResManCorForm").serializeArray();
    var act=JSON.stringify(listaAct);
    datos.push({name: 'tip', value: 'ingOrd'});
    datos.push({name: 'act', value: act});
    $.ajax
    ({
        url : '../datos/datos.ingresa_resultados_corr.php',
        type : 'POST',
        dataType : 'json',
        data :  datos ,
        success : function(json) {
            if(json){
                if(json['res']=="true"){
                    swal({
                            title: "Mensaje",
                            text: "Has guardado exitosamente la orden",
                            type: "success"},
                        function(isConfirm){
                            if (isConfirm) {
                                $('#ingResManCorForm')[0].reset();
                                $("#ingResManInpCodSis").focus();
                                listaAct=[];

                            }
                        });
                }else{
                    swal({
                            title: "Mensaje",
                            text: "error "+json['error'],
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                                $("#ingResManInpCodSis").focus();
                            }
                        });

                }


            }
            else
            {

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


function completaDatos()
{

    $.ajax
    ({
        url : '../datos/datos.ingresa_resultados_corr.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'lleDat',inm:$("#ingResManInpCodSis").val() },
        success : function(json) {
            if(json){

                $("#ingResManCorInpProy").val(json[0]['ID_PROYECTO']);
                $("#ingResManCorInpAcu").val(json[0]['SIGLA_PROYECTO']);
                $("#ingResManCorInpEmpPla").val(json[0]['NOMBRE_OPER']);
                $("#ingResManCorInpFechPla").val(json[0]['FECHA_GENORDEN']);
                $("#ingResManCorInpMot").val(json[0]['DESCRIPCION']);
                $("#ingResManCorInpOrd").val(json[0]['ID_ORDEN']);
                $("#ingResManCorInpCodCli").val(json[0]['CODIGO_CLI']);
                $("#ingResManCorInpCli").val(json[0]['NOMBRE']);
                $("#ingResManCorInpDir").val(json[0]['DIRECCION']);
                $("#ingResManCorInpZon").val(json[0]['ID_ZONA']);
                $("#ingResManCorInpCodMedRet").val(json[0]['COD_MEDIDOR']);
                $("#ingResManCorInpMedRet").val(json[0]['DESC_MED']);
                $("#ingResManCorInpCalRet").val(json[0]['DESC_CALIBRE']);
                $("#ingResManCorInpEmplaRet").val(json[0]['DESC_EMPLAZAMIENTO']);
                $("#ingResManCorInpSerRet").val(json[0]['SERIAL']);

            }
            else
            {
                if($("#ingResManInpCodSis").val()!=''){
                    swal({
                            title: "Mensaje",
                            text: "No existe una orden abierta para el inmueble",
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                                $("#ingResManInpCodSis").focus();
                            }
                        });

                }

                $('#ingResManCorForm')[0].reset();

            }
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


function llenarSelMed()
{

    $.ajax
    ({
        url : '../datos/datos.ingresa_resultados_corr.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selMed' },
        success : function(json) {
            $('#ingResManCorSelMarcMedIns').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#ingResManCorSelMarcMedIns').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelCal()
{

    $.ajax
    ({
        url : '../datos/datos.ingresa_resultados_corr.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCal' },
        success : function(json) {
            $('#ingResManCorSelCalIns').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#ingResManCorSelCalIns').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelEmp()
{

    $.ajax
    ({
        url : '../datos/datos.ingresa_resultados_corr.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEmp' },
        success : function(json) {
            $('#ingResManCorSelEmpIns').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#ingResManCorSelEmpIns').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
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

                            iniSes();
                        }
                    });
            }
        },
        error : function(xhr, status) {
            return false;
        }
    });

}

//FUNCION PARA ABRIR UN POPUP
var popped = null;
function popup(uri, awid, ahei, scrollbar) {
    var params;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        }
        else {
            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
            popped = window.open(uri, "popup", params);
        }
    }
}
