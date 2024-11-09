/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyDeuOfiPer;
var inpPerDeuOfiPer;
var inpPerFinDeuOfiPer;
var butGenDeuOfiPer;
var genRepDeuOfiForm;
var inpHidValRes;

function repDeuOfiPerInicio(){
	selProyDeuOfiPer=document.getElementById("selProyDeuOfiPer");
	inpPerDeuOfiPer=document.getElementById("inpPerDeuOfiPer");
    inpPerFinDeuOfiPer=document.getElementById("inpPerFinDeuOfiPer");
	butGenDeuOfiPer=document.getElementById("butGenDeuOfiPer");
	genRepDeuOfiForm=document.getElementById("genRepDeuOfiForm");
	/*butGenDeuOfiPer.addEventListener("click",verificaSession);*/
	repDeuOfiPerSelPro();
	/*genRepDeuOfiForm.addEventListener("submit",genRepDeuOfiForm )*/
    $("#genRepDeuOfiForm").submit(
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
                        compSession(genRepDeuGraCliDat);
                    }
                });
        })
}

function genRepDeuGraCliDat(){
    var datos=$("#genRepDeuOfiForm").serializeArray();
    datos.push({name:'tip',value:'DeuOfiPer'});
    $.ajax
    ({
        url : '../datos/datos.datRepDeuOfiPer.php',
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
function repDeuOfiPerSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyDeuOfiPer.add(option,selProyDeuOfiPer[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyDeuOfiPer.add(option,selProyDeuOfiPer[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepDeuOfiPer.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}


function validaCampos(){
    if(selProyDeuOfiPer.selectedIndex==0){
		selProyDeuOfiPer.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpPerDeuOfiPer.value==""){
		inpPerDeuOfiPer.focus();
        swal("Error!", "Debe indicar un periodo", "error");
        return false;
    }
	
    return true;
}

function genRepDeuOfiPerDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepDeuOfiPer.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "DeuOfiPer");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "periodo");
	campo2.setAttribute("value", inpPerDeuOfiPer.value);
	form.appendChild(campo2);
    var campo4 = document.createElement("input");
    campo4.setAttribute("type", "hidden");
    campo4.setAttribute("name", "periodof");
    campo4.setAttribute("value", inpPerFinDeuOfiPer.value);
    form.appendChild(campo4);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "proyecto");
	campo3.setAttribute("value", selProyDeuOfiPer.value);
	form.appendChild(campo3);
	document.body.appendChild(form);
	form.submit(); 
}


function verificaSession(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
				if(validaCampos()){
					 swal({
                     	title: "Mensaje",
                        text: "La descarga del archivo puede tomar algunos minutos.\nDesea descargarlo ahora?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si!",
                        cancelButtonText: "No!",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: true 
					},
                    function(isConfirm){
                    	if (isConfirm) {
                        	genRepDeuOfiPerDat();
                        }
                    });
				}
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
							window.close(this);
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepDeuOfiPer.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
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