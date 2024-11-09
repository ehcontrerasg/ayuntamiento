/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyUniGerUsoCon;
var inpPerUniGerUsoCon;
var butGenUniGerUsoCon;
var genRepUniGerUsoConForm;
var inpHidValRes;

function repUniGerUsoConInicio(){
	selProyUniGerUsoCon=document.getElementById("selProyUniGerUsoCon");
	inpPerUniGerUsoCon=document.getElementById("inpPerUniGerUsoCon");
	butGenUniGerUsoCon=document.getElementById("butGenUniGerUsoCon");
	genRepUniGerUsoConForm=document.getElementById("genRepUniGerUsoConForm");
	butGenUniGerUsoCon.addEventListener("click",verificaSession);
	repUniGerUsoConPro();
	genRepUniGerUsoConForm.addEventListener("submit",genRepUniGerUsoConForm );
}

function repUniGerUsoConPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyUniGerUsoCon.add(option,selProyUniGerUsoCon[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyUniGerUsoCon.add(option,selProyUniGerUsoCon[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepUniGerUsoCon.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}


function validaCampos(){
    if(selProyUniGerUsoCon.selectedIndex==0){
		selProyUniGerUsoCon.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpPerUniGerUsoCon.value==""){
		inpPerUniGerUsoCon.focus();
        swal("Error!", "Debe indicar un periodo", "error");
        return false;
    }
	
    return true;
}

function genRepUniGerUsoConDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepUniGerUsoCon.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "UniGerUsoCon");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "periodo");
	campo2.setAttribute("value", inpPerUniGerUsoCon.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "proyecto");
	campo3.setAttribute("value", selProyUniGerUsoCon.value);
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
                            genRepUniGerUsoConDat();
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
    xmlhttp.open("POST", "../datos/datos.datRepUniGerUsoCon.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}