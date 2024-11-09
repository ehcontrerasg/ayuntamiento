/**
 * Created by Algenis Mosquea on 22/01/2018.
 */
var selProyRecGraCli;
var inpFecIniRecGraCli;
var inpFecFinRecGraCli;
var butGenRecGraCli;
var genRepRecGraCliForm;
var inpHidValRes;
var inpInmueble;

function repRecGraCliInicio(){
	selProyRecGraCli=document.getElementById("selProyRecGraCli");
	inpFecIniRecGraCli=document.getElementById("inpFecIniRecGraCli");
	inpFecFinRecGraCli=document.getElementById("inpFecFinRecGraCli");
	butGenRecGraCli=document.getElementById("butGenRecGraCli");
	genRepRecGraCliForm=document.getElementById("genRepRecGraCliForm");
	inpInmueble = document.getElementById("inpInmueble");
	butGenRecGraCli.addEventListener("click",verificaSession);
	repRecGraCliPro();
	genRepRecGraCliForm.addEventListener("submit",genRepRecGraCliForm );
}

function repRecGraCliPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyRecGraCli.add(option,selProyRecGraCli[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyRecGraCli.add(option,selProyRecGraCli[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRecCli.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}


function validaCampos(){
    if(selProyRecGraCli.selectedIndex==0){
		selProyRecGraCli.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    if(inpFecIniRecGraCli.value==""){
		inpFecIniRecGraCli.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	if(inpFecFinRecGraCli.value==""){
		inpFecFinRecGraCli.focus();
        swal("Error!", "Debe indicar una fecha final", "error");
        return false;
    }
    return true;
}

function genRepRecGraCliDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRecCli.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "RecGraCli");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "proyecto");
	campo2.setAttribute("value", selProyRecGraCli.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "fechaini");
	campo3.setAttribute("value", inpFecIniRecGraCli.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "fechafin");
	campo4.setAttribute("value", inpFecFinRecGraCli.value);
	form.appendChild(campo4);
	var campo5 = document.createElement("input");
	campo5.setAttribute("type", "hidden");
	campo5.setAttribute("name", "inmueble");
	campo5.setAttribute("value", inpInmueble.value);
	form.appendChild(campo5);
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
                        closeOnConfirm: true,
                        closeOnCancel: true 
					},
                    function(isConfirm){
                    	if (isConfirm) {
                        	genRepRecGraCliDat();
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
    xmlhttp.open("POST", "../datos/datos.datRecCli.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}