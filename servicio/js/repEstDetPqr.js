/**
 * Created by Algenis Mosquea on 12/01/2018.
 */

var selProyEstPqr;
var inpFecIniEstPqr;
var inpFecFinEstPqr;
var butGenEstPqr;
var genRepEstPqrForm;
var inpHidValRes;
var inpTipoPqr;
var motivo_pqr;

function repEstPqrInicio(){
	selProyEstPqr=document.getElementById("selProyEstPqr");
	inpFecIniEstPqr=document.getElementById("inpFecIniEstPqr");
	inpFecFinEstPqr=document.getElementById("inpFecFinEstPqr");
	inpHidValRes=document.getElementById("inpHidValRes");
	inpTipoPqr=document.getElementById("inpTipoPqr");
	motivo_pqr=document.getElementById("inpMotivoPqr");
	butGenEstPqr=document.getElementById("butGenEstPqr");
	genRepEstPqrForm=document.getElementById("genRepEstPqrForm");
	butGenEstPqr.addEventListener("click",verificaSession);
	genRepEstPqrForm.addEventListener("submit",genRepEstPqrForm );

    inpTipoPqr.addEventListener("change",getMotivoPqr);
}

function getMotivoPqr(e)
{
    var data = e.target.selectedIndex;
    motivo_pqr.innerHTML = '';

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            var motivos = JSON.parse(datos);
            var option = document.createElement('option');
                option.setAttribute('value', '0');
                option.innerText = 'Motivo PQR:';
                motivo_pqr.appendChild(option);

            console.log(motivos['DESC_MOTIVO_REC'][0])
            for(var i = 0 ; i < motivos['DESC_MOTIVO_REC'].length; i++){
                var option = document.createElement('option');
                option.setAttribute('value', motivos['ID_MOTIVO_REC'][i]);
                option.innerText = motivos['ID_MOTIVO_REC'][i] + '-' + motivos['DESC_MOTIVO_REC'][i];

                motivo_pqr.appendChild(option);
            };
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepEstDetPqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=motiv&group="+data);

}

function validaCampos(){
    if(selProyEstPqr.selectedIndex==0){
		selProyEstPqr.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    if(inpTipoPqr.selectedIndex==0){
		inpTipoPqr.focus();
        swal("Error!", "Debe indicar un tipo de PQR", "error");
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
   /* var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepEstDetPqr.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "EstPqr");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "fecini");
	campo2.setAttribute("value", inpFecIniEstPqr.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "fecfin");
	campo3.setAttribute("value", inpFecFinEstPqr.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "proyecto");
	campo4.setAttribute("value", selProyEstPqr.value);
	form.appendChild(campo4);
	var campo5 = document.createElement("input");
	campo5.setAttribute("type", "hidden");
	campo5.setAttribute("name", "tipo_pqr");
	campo5.setAttribute("value", inpTipoPqr.value);
	form.appendChild(campo5);
	var campo6 = document.createElement("input");
	campo6.setAttribute("type", "hidden");
	campo6.setAttribute("name", "motivo_pqr");
	campo6.setAttribute("value", motivo_pqr.value);
	form.appendChild(campo6);

	document.body.appendChild(form);
	form.submit(function(resp){
        console.log(resp);
    });*/

    var datos=$("#genRepEstPqrForm").serializeArray();
    datos.push({name:"tip",value:"EstPqr"});
    $.ajax
    ({
        url : '../reportes/reporte.repEstDetPqr.php',
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
                        	genRepEstPqrDat();
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
							 top.location.replace("../../index.php");
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepEstPqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}