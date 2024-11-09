$(document).ready(function(){

    desContr();
    compSession();
    compSession(llenarSpinerContr)



});

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
                $("#rendCorSelPro").focus(false);
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
                            $("#rendCorSelPro").focus();
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

// selecionar el valor (id) del select contratista
function selecionar(valor)
{
    
     // recorremos todos los valores del select
     for(var i=1;i<$('#selContr > option').length;i++)

    {
        var select=document.getElementById("selContr");
        for(var i=1;i<select.length;i++)
        {

             if(select.options[i].value==valor)
             {
            //     // seleccionamos el valor que coincide
                 select.selectedIndex=i;
             }
        }

    }
};

// llena el listbox contratista
function llenarSpinerContr()
{

    $.ajax
    ({
        url : '../datos/datos.reporteDiario.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCon' },
        success : function(json) {
            $('#selContr').empty();
            $('#selContr').append(new Option('', '', true, true));


            for(var x=0;x<json.length;x++)
            {

                 $('#selContr').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

            }

            select();



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

