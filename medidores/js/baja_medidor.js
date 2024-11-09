$(document).ready(function(){
    desContr();
    compSession();
    $("#bajMedForm").submit(
        function(){
            compSession(guardaOrden);
        }
    )
    $("#bajMedInpCodSis").blur(
        function(){

            if($("#bajMedInpCodSis").val()!=''){
                compSession(completaDatos);
            }else{
                $("#bajMedInpCodSis").focus();
                $('#bajMedForm')[0].reset();
            }

        }
    )
});

function guardaOrden(){

    var datos=$("#bajMedForm").serializeArray();
    datos.push({name: 'tip', value: 'bajMed'});
    $.ajax
    ({
        url : '../datos/datos.baja_medidor.php',
        type : 'POST',
        dataType : 'json',
        data :  datos ,
        success : function(json) {
            if(json){
                if(json['res']=="true"){
                    swal({
                            title: "Mensaje",
                            text: "Has dado de baja al medidor",
                            type: "success"},
                        function(isConfirm){
                            if (isConfirm) {
                                $('#bajMedForm')[0].reset();
                                $("#bajMedInpCodSis").focus();
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
                                $("#bajMedInpCodSis").focus();
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
        url : '../datos/datos.baja_medidor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'lleDat',inm:$("#bajMedInpCodSis").val() },
        success : function(json) {
            if(json){

                $("#bajMedInpProy").val(json[0]['ID_PROYECTO']);
                $("#bajMedInpAcu").val(json[0]['SIGLA_PROYECTO']);
                $("#bajMedInpCodCli").val(json[0]['CODIGO_CLI']);
                $("#bajMedInpCli").val(json[0]['NOMBRE']);
                $("#bajMedInpDir").val(json[0]['DIRECCION']);
                $("#bajMedInpZon").val(json[0]['ID_ZONA']);
                $("#bajMedInpCodMedRet").val(json[0]['COD_MEDIDOR']);
                $("#bajMedInpMedRet").val(json[0]['DESC_MED']);
                $("#bajMedInpCalRet").val(json[0]['DESC_CALIBRE']);
                $("#bajMedInpEmplaRet").val(json[0]['DESC_EMPLAZAMIENTO']);
                $("#bajMedInpSerRet").val(json[0]['SERIAL']);

            }
            else
            {
                if($("#bajMedInpCodSis").val()!=''){
                    swal({
                            title: "Mensaje",
                            text: "El inmueble no existe o no tiene un medidor activo",
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                                $("#bajMedInpCodSis").focus();
                            }
                        });

                }

                $('#bajMedForm')[0].reset();
                $("#bajMedInpCodSis").focus();

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
