var cantTot;
$(document).ready(function(){
    desContr();
    compSession();
    compSession(llenarSpinerPro);
    compSession(llenarSpinerContr);






    $('#formulario').submit(
        function(){
            swal
            ({
                    title: "Advertencia!",
                    text: "El reporte demorara unos minutos en salir.",
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
                        compSession(generaImp);
                    }
                });


        }
    );


    function generaImp(){
        var datos=$("#formulario").serializeArray();
        $.ajax
        ({
            url : '../reportes/reporte.corXFecAsig.php',
            type : 'POST',
            dataType : 'text',
            data : datos ,
            success : function(urlPdf) {

                if (urlPdf.substr(0,11)=="../../temp/"){
                    swal("Mensaje!", "Has Generado correctamente los datos", "success");
                        window.location.href = urlPdf;

                    
                    //

                }else{
                    swal
                    (
                        {
                            title: "Error",
                            text: "Contacte a sistemas",
                            type: "error",
                            html: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true});

                }

            },
            error : function(xhr, status) {
                swal("Error!", "error desconocido contacte a sistemas", "error");
            }
        });

    }




});

function llenarSpinerContr() {

    $.ajax
    ({
        url: '../../general/datos/datos.contratista.php',
        type: 'POST',
        dataType: 'json',
        data: {tip: 'selCon'},
        success: function (json) {
            $('#contratista').empty();
            $('#contratista').append(new Option('', '', true, true));

            for (var x = 0; x < json.length; x++) {

                $('#contratista').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

            }

        },
        error: function (xhr, status) {

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
        url : '../datos/datos.repCortConLect.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#proyecto').empty();
            $('#proyecto').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#proyecto').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
                            $("#revAleSelPro").focus();
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
