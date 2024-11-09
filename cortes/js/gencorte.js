var xid=0;
$(document).ready(function(){

    desContr();
    compSession();
    compSession(llenarSpinerPro)

    $("#genCorSelPro").change(
        function(){
            compSession(llenarSpinerUsu);
        }
    );



    $('#genCorForm').submit(
        function(){
            swal
            ({
                    title: "Advertencia!",
                    text: "desea generar un corte para este inmueble?.",
                    showConfirmButton: true,
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        compSession(genCorte);
                    }
                });
        }
    )

});


function llenarSpinerUsu()
{

    $.ajax
    ({
        url : '../datos/datos.gencorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUsu',pro:$('#genCorSelPro').val() },
        success : function(json) {
            $('#genCorSelOpe').empty();
            $('#genCorSelOpe').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genCorSelOpe').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.gencorte.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#genCorSelPro').empty();
            $('#genCorSelPro').append(new Option('', '', true, true));

            for(var x=0;x<json.length;x++)
            {
                $('#genCorSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}







function genCorte(){
    var datos=$("#genCorForm").serializeArray();
    datos.push({name: 'tip', value: 'genOrd'});
    $.ajax
    ({
        url : '../datos/datos.gencorte.php',
        type : 'POST',
        dataType : 'json',
        data : datos ,
        success : function(json) {

            if(json){
                if(json["res"]=="true"){
                    swal({
                            title: "Mensaje",
                            text: "Has generado exitosamente la orden",
                            type: "success"},
                        function(isConfirm){
                            if (isConfirm) {
                                $('#genCorForm')[0].reset();
                                $("#genCorInpInm").focus();

                            }
                        });
                }else if(json["res"]=="false"){
                    swal({
                            title: "Mensaje",
                            text: "error "+json['error'],
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {
                                $("#genCorInpInm").focus();
                            }
                        });

                }


            }

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
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
                            $("#genCorInpInm").focus();
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
                            $("#genCorInpInm").focus();
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


