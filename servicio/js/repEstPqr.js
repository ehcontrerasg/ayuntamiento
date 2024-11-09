/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyEstPqr;
var inpFecIniEstPqr;
var inpFecFinEstPqr;
var butGenEstPqr;
var genRepEstPqrForm;
var inpHidValRes;

function repEstPqrInicio(){
    selProyEstPqr=document.getElementById("selProyEstPqr");
    inpFecIniEstPqr=document.getElementById("inpFecIniEstPqr");
    inpFecFinEstPqr=document.getElementById("inpFecFinEstPqr");
    inpHidValRes=document.getElementById("inpHidValRes");
    butGenEstPqr=document.getElementById("butGenEstPqr");
    genRepEstPqrForm=document.getElementById("genRepEstPqrForm");
    //butGenEstPqr.addEventListener("click",verificaSession);
    repEstPqrSelPro();
    //genRepEstPqrForm.addEventListener("submit",genRepEstPqrForm );
    $("#genRepEstPqrForm").submit(
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
                {   if (isConfirm)
                {
                    compSession(genRepEstPqrDat);
                }
                });
        })


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

function repEstPqrSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selProyEstPqr.add(option,selProyEstPqr[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                selProyEstPqr.add(option,selProyEstPqr[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepEstPqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function validaCampos(){
    if(selProyEstPqr.selectedIndex==0){
        selProyEstPqr.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpFecIniEstPqr.value==""){
        inpFecIniEstPqr.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }

    if(inpFecFinEstPqr.value==""){
        inpFecFinEstPqr.focus();
        swal("Error!", "Debe indicar una fecha final", "error");
        return false;
    }

    return true;
}




function genRepEstPqrDat(){
    var datos=$("#genRepEstPqrForm").serializeArray();

    $.ajax
    ({
        url : '../reportes/reporte.repEstPqr.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){

                window.location.href = urlPdf;
                //window.close();
                swal("Mensaje!", "Has Generado correctamente el reporte", "success");
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