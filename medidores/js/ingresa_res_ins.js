$(document).ready(function(){


    desContr();
    compSession();
    compSession(llenarSelArea);

    $("#ingResInsInpCodSis").blur(
        function(){
            if($(this).val()=='' ){
                $('#ingResInsMedForm')[0].reset();
                $(this).focus();
                $("#ingResInsSelInp option[value='"+"']").attr("selected",true);

            }else{
                compSession(completaDatos);
            }

        }
    )
    $("#ingResInsInpCodSis").dblclick(
        function(){popup("vista.lista_ins_act.php",638,400,'yes');

        }
    )

    $("#ingResInsMedForm").submit(
        function(){
            compSession(IngIns);
        }
    )


});


function llenarSelArea()
{

    $.ajax
    ({
        url : '../datos/datos.ing_res_ins.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selAre' },
        success : function(json) {
            $('#ingResInsSelInp').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#ingResInsSelInp').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
            }
            $("#ingResInsSelInp option[value='"+"']").attr("selected",true);
        },
        error : function(xhr, status) {

        }
    });
}





function IngIns(){

    var datos=$("#ingResInsMedForm").serializeArray();
    datos.push({name: 'tip', value: 'ingIns'});

    $.ajax
    ({
        url : '../datos/datos.ing_res_ins.php',
        type : 'POST',
        data : datos,
        dataType : 'json',
        success : function(json) {
            if (json["res"]=="true"){
                $('#ingResInsMedForm')[0].reset();
                swal({
                        title: "Mensaje",
                        text: "Has ingresado la orden correctamente",
                        type: "success"},
                    function(isConfirm){
                        if (isConfirm) {
                            $("#ingResInsInpCodSis").focus();
                        }
                    }
                );
            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
                $(this).focus();
            }
        },
        error : function(xhr, status) {

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

function completaDatos(){
    $.ajax
    ({
        url : '../datos/datos.ing_res_ins.php',
        type : 'POST',
        data : { tip : 'obtDat',inm:$("#ingResInsInpCodSis").val() },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                $("#ingResInsInpPro").val(json[0]['PROYECTO_COD']);
                $("#ingResInsInpAcu").val(json[0]['PROYECTO_DESC']);
                $("#ingResInsInpOpe").val(json[0]['USU_INSMED']);
                $("#ingResInsInpFecPla").val(json[0]['FECH_GENINSMED']);
                $("#ingResInsInpMot").val(json[0]['MOTRECL']);
                $("#ingResInsInpOrd").val(json[0]['ODEN_INSMED']);
                $("#ingResInsInpCodCli").val(json[0]['CODIGO_CLI']);
                $("#ingResInsInpNomCli").val(json[0]['NOMBRE_CLI']);
                $("#ingResInsInpDir").val(json[0]['DIRECCION']);
                $("#ingResInsInpZon").val(json[0]['ZONA']);
                $("#ingResInsTexAreDescIns").val(json[0]['DESCINS']);

                $("#ingResInsInpCodMed").val(json[0]['MEDIDOR_COD']);
                $("#ingResInsInpDesMed").val(json[0]['MEDIDOR_DESC']);
                $("#ingResInsInpCal").val(json[0]['CALIBRE_DESC']);
                $("#ingResInsInpEmp").val(json[0]['EMPLAZAMIENTO_DESC']);
                $("#ingResInsInpSer").val(json[0]['SERIAL']);
                $("#ingResInsInpPqr").val(json[0]['PQR']);
            }else{


                swal({
                        title: "Mensaje",
                        text: "No existe una orden de inspección abierta para el inmueble " +
                        "desea generar una Observacion ?",
                        type: "error",
                        html: true,
                        showCancelButton: true,
                        confirmButtonText: "Si, Generar",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm){
                        if (isConfirm) {

                           obsMan();
                        }else{
                            $('#ingResInsMedForm')[0].reset();
                            $("#ingResInsInpCodSis").focus();}
                    });
            }
        },
        error : function(xhr, status) {

            return false;
        }
    });

}


function obsMan(){
    swal({
            html: true,
            title: "Observacion Manual!",
            text: "Ingrese la Observación: <br><center> <input class='estilo-inp' type='text' required id='obsMan'> </center>",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Observación"
        },
        function(inputValue){
            if (inputValue === false)
                return false;
            if (inputValue === "") {
                swal.showInputError("Error no has ingresado ninguna observación !");
                return false
            }else{
                ingresaObs($("#obsMan").val());
            }
        });
}

function ingresaObs(valObs){

    $.ajax
    ({
        url : '../datos/datos.ing_res_ins.php',
        type : 'POST',
        data : { tip : 'IngObs',inm:$("#ingResInsInpCodSis").val(),obs:valObs,
        asunto:'INSPECCION GEN. MANUAL',codObs:'INP'},
        dataType : 'json',
        success : function(json) {
            if (json["res"]=="true"){
                $('#ingResInsMedForm')[0].reset();
                swal({
                        title: "Mensaje",
                        text: "Has ingresado la Observacion correctamente",
                        type: "success"},
                    function(isConfirm){
                        if (isConfirm) {
                            $("#ingResInsInpCodSis").focus();
                        }
                    }
                );
            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
                $(this).focus();
            }
        },
        error : function(xhr, status) {
            alert("error");
            return false;
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
                $("#ingResInsInpCodSis").focus(false);
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
                        inputPlaceholder: "Write something",
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
                    });
            }
        },
        error : function(xhr, status) {
            alert("error");
            return false;
        }
    });

}


