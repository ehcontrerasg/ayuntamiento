/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyMetMesAnt;
var inpPerMetMesAnt;
var butGenMetMesAnt;
var genRepMetMesAntForm;
var inpHidValRes;

function repMetMesAntInicio(){
	selProyMetMesAnt=document.getElementById("selProyMetMesAnt");
	inpPerMetMesAnt=document.getElementById("inpPerMetMesAnt");
	butGenMetMesAnt=document.getElementById("butGenMetMesAnt");
	genRepMetMesAntForm=document.getElementById("genRepMetMesAntForm");
	butGenMetMesAnt.addEventListener("click",verificaSession);
	repMetMesAntSelPro();
	genRepMetMesAntForm.addEventListener("submit",genRepMetMesAntForm );
}

function repMetMesAntSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyMetMesAnt.add(option,selProyMetMesAnt[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyMetMesAnt.add(option,selProyMetMesAnt[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepMetMesAnt.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}


function validaCampos(){
    if(selProyMetMesAnt.selectedIndex==0){
		selProyMetMesAnt.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpPerMetMesAnt.value==""){
		inpPerMetMesAnt.focus();
        swal("Error!", "Debe indicar un periodo", "error");
        return false;
    }
	
    return true;
}

function genRepMetMesAntDat(){
    /*var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepMetMesAnt.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "MetMesAnt");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "periodo");
	campo2.setAttribute("value", inpPerMetMesAnt.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "proyecto");
	campo3.setAttribute("value", selProyMetMesAnt.value);
	form.appendChild(campo3);
	document.body.appendChild(form);
	form.submit(); */

    var datos=$("#genRepMetMesAntForm").serializeArray();
    datos.push({name:'tip',value:'MetMesAnt'});
    $.ajax
    ({
        url : '../datos/datos.datRepMetMesAnt.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){

                /*console.log(urlPdf);*/
                window.location.href = urlPdf;


                swal
                (
                    {
                        title: "Reporte Generado!",
                        text: "Has generado correctamente el reporte",
                        type: "success",
                        html: true,
                        confirmButtonColor: "#66CC33",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true},
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();

                        }
                    });

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
                        	genRepMetMesAntDat();
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
    xmlhttp.open("POST", "../datos/datos.datRepMetMesAnt.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}