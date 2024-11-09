/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyResolGer;
var inpFecIniResolGer;
var inpFecFinResolGer;
var butGenResolGer;
var genRepResolGerForm;
var inpHidValRes;

function repResolGerInicio(){
	selProyResolGer=document.getElementById("selProyResolGer");
	inpFecIniResolGer=document.getElementById("inpFecIniResolGer");
	inpFecFinResolGer=document.getElementById("inpFecFinResolGer");
	inpHidValRes=document.getElementById("inpHidValRes");
	butGenResolGer=document.getElementById("butGenResolGer");
	genRepResolGerForm=document.getElementById("genRepResolGerForm");
	/*butGenResolGer.addEventListener("click",verificaSession);*/
	repResolGerSelPro();
	/*genRepResolGerForm.addEventListener("submit",genRepResolGerForm );*/
    $("#genRepResolGerForm").submit(
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
                        /*compSession();*/
                        genRepResPorGerencia();
                        /*compSession(genRepRecGraCliForm);*/
                    }
                });
        }


    )
}

function genRepResPorGerencia(){
    var datos=$("#genRepResolGerForm").serializeArray();

    $.ajax
    ({
        url : '../reportes/reporte.reporteResolucionesGerencia.php',
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
           /* alert(urlPdf);*/
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

function repResolGerSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyResolGer.add(option,selProyResolGer[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyResolGer.add(option,selProyResolGer[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepResolGer.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function validaCampos(){
    if(selProyResolGer.selectedIndex==0){
		selProyResolGer.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpFecIniResolGer.value==""){
		inpFecIniResolGer.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	
	if(inpFecFinResolGer.value==""){
		inpFecFinResolGer.focus();
        swal("Error!", "Debe indicar una fecha final", "error");
        return false;
    }
	
    return true;
}

function genRepResolGerDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepResolGer.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "ResolGer");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "fecini");
	campo2.setAttribute("value", inpFecIniResolGer.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "fecfin");
	campo3.setAttribute("value", inpFecFinResolGer.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "proyecto");
	campo4.setAttribute("value", selProyResolGer.value);
	form.appendChild(campo4);
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
                        	genRepResolGerDat();
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
    xmlhttp.open("POST", "../datos/datos.datRepResolGer.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

