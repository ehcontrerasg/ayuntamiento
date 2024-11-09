$(document).ready(function(){
    desContr();
    compSession();
    $("#camDatMedForm").submit(
        function(){
            compSession(cambiaDat);
        }
    )
    $("#camDatMedInpCodSis").blur(
        function(){

            if($("#camDatMedInpCodSis").val()!=''){
                compSession(completaDatos);
            }else{
                $("#camDatMedInpCodSis").focus();
                $('#camDatMedForm')[0].reset();
                $('#camDatMedSelCodMed').empty();
                $('#camDatMedSelCal').empty();
                $('#camDatMedSelEmpla').empty();
            }

        }
    )
});

function cambiaDat(){

    var datos=$("#camDatMedForm").serializeArray();
    datos.push({name: 'tip', value: 'camDat'});
    $.ajax
    ({
        url : '../datos/datos.cambio_datos.php',
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
                                $('#camDatMedForm')[0].reset();
                                $('#camDatMedSelCodMed').empty();
                                $('#camDatMedSelCal').empty();
                                $('#camDatMedSelEmpla').empty();
                                $("#camDatMedInpCodSis").focus();
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
                                $("#camDatMedInpCodSis").focus();
                            }
                        });
                    $("#camDatMedInpCodSis").focus();

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
        url : '../datos/datos.cambio_datos.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'lleDat',inm:$("#camDatMedInpCodSis").val() },
        success : function(json) {
            if(json){

                $("#camDatMedInpProy").val(json[0]['ID_PROYECTO']);
                $("#camDatMedInpAcu").val(json[0]['SIGLA_PROYECTO']);
                $("#camDatMedInpCodCli").val(json[0]['CODIGO_CLI']);
                $("#camDatMedInpCli").val(json[0]['NOMBRE']);
                $("#camDatMedInpDir").val(json[0]['DIRECCION']);
                $("#camDatMedInpZon").val(json[0]['ID_ZONA']);
                llenarSelCal(json[0]['DESC_CALIBRE']);
                llenarSelEmp(json[0]['DESC_EMPLAZAMIENTO']);
                llenarSelMed(json[0]['DESC_MED']);
                $("#camDatMedInpSer").val(json[0]['SERIAL']);

            }
            else
            {
                if($("#camDatMedInpCodSis").val()!=''){
                    swal({
                            title: "Mensaje",
                            text: "El inmueble no existe o no tiene un medidor activo",
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                                $("#camDatMedInpCodSis").focus();
                            }
                        });

                }

                $('#camDatMedForm')[0].reset();
                $('#camDatMedSelCodMed').empty();
                $('#camDatMedSelCal').empty();
                $('#camDatMedSelEmpla').empty();
                $("#camDatMedInpCodSis").focus();

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


function llenarSelMed(medAct)
{

    $.ajax
    ({
        url : '../datos/datos.cambio_datos.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selMed' },
        success : function(json) {
            $('#camDatMedSelCodMed').empty();
            for(var x=0;x<json.length;x++)
            {

                if(medAct==json[x]["DESCRIPCION"]){
                    $('#camDatMedSelCodMed').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
                }else{
                    $('#camDatMedSelCodMed').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelCal(calAct)
{

    $.ajax
    ({
        url : '../datos/datos.cambio_datos.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCal' },
        success : function(json) {
            $('#camDatMedSelCal').empty();
            for(var x=0;x<json.length;x++)
            {
                if(calAct==json[x]["DESCRIPCION"]){
                    $('#camDatMedSelCal').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
                }else{
                    $('#camDatMedSelCal').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSelEmp(empAct)
{

    $.ajax
    ({
        url : '../datos/datos.cambio_datos.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selEmp' },
        success : function(json) {
            $('#camDatMedSelEmpla').empty();
            for(var x=0;x<json.length;x++)
            {
                if(empAct==json[x]["DESCRIPCION"]){
                    $('#camDatMedSelEmpla').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
                }else{
                    $('#camDatMedSelEmpla').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

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
