/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyInmPdc;
var butGenInmPdc;
var genRepInmPdcForm;
var inpHidValRes;

function repInmPdcInicio(){
	selProyInmPdc=document.getElementById("selProyInmPdc");
//	inpHidValRes=document.getElementById("inpHidValRes");
	butGenInmPdc=document.getElementById("butGenInmPdc");
	genRepInmPdcForm=document.getElementById("genRepInmPdcForm");
	butGenInmPdc.addEventListener("click",verificaSession);
	repInmPdcSelPro();
	genRepInmPdcForm.addEventListener("submit",genRepInmPdcForm );
}

function repInmPdcSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyInmPdc.add(option,selProyInmPdc[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyInmPdc.add(option,selProyInmPdc[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepInmPdc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function validaCampos(){
    if(selProyInmPdc.selectedIndex==0){
		selProyInmPdc.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

   /* if(inpFecIniResRecEnt.value==""){
		inpFecIniResRecEnt.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	
	if(inpFecFinResRecEnt.value==""){
		inpFecFinResRecEnt.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	*/
	
    return true;
}

function genRepInmPdcDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepInmPdc.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "InmPdc");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "proyecto");
	campo2.setAttribute("value", selProyInmPdc.value);
	form.appendChild(campo2);
	document.body.appendChild(form);
	form.submit(); 
}

/*function genRepHisFacDat()
{
    var proyecto=selProyHisFac.value, periodo=inpPerHisFac.value, gerencia=selGerHisFac.value, uso=selUsoHisFac.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if (datos=="1"){
                swal("Mensaje!", "Has Generado correctamente el archivo", "success");
            }else if(datos["res"]=="false"){
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
            inpHidValRes.data=datos;
        }
    }
	xmlhttp.open("POST", "../datos/datos.datRepHisFac.php", true); 
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=hisFac"+"&proyecto="+proyecto+"&periodo="+periodo+"&gerencia="+gerencia+"&uso="+uso);
}*/

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
                        	genRepInmPdcDat();
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
    xmlhttp.open("POST", "../datos/datos.datRepInmPdc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}// JavaScript Document