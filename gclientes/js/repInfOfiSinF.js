/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */
var selProyInfOfiSinF;
var inpPerInfOfiSinF;
var inpPerFinInfOfiSinF;
var butGenInfOfiSinF;
var genRepInfOfiSinFForm;
var selZonInfOfiSinF;
var selGruInfOfiSinF;
var inpHidValRes;
var inpZonOfiSinF;
var inpDocumentoSinF;

function repInfGraCliInicio(){
	selProyInfOfiSinF=document.getElementById("selProyInfOfiSinF");
    selZonInfOfiSinF=document.getElementById("selZonInfOfiSinF");
    selGruInfOfiSinF=document.getElementById("selGruInfOfiSinF");
	inpPerInfOfiSinF=document.getElementById("inpPerInfOfiSinF");
    inpPerFinInfOfiSinF=document.getElementById("inpPerFinInfOfiSinF");
    inpZonOfiSinF=document.getElementById("inpZonOfiSinF");
    inpDocumentoSinF=document.getElementById("inpDocumentoSinF");
	butGenInfOfiSinF=document.getElementById("butGenInfOfiSinF");
	genRepInfOfiSinFForm=document.getElementById("genRepInfOfiSinForm");
	butGenInfOfiSinF.addEventListener("click",verificaSession);
	repInfOfiSinFPro();
    repInfOfiSinFGru();



    $("#genRepInfOfiSinForm").submit(
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
                        compSession(genRepInfOfiSinFForm);
                    }
                });
        }


    )

}

function repInfOfiSinFPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyInfOfiSinF.add(option,selProyInfOfiSinF[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyInfOfiSinF.add(option,selProyInfOfiSinF[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datInfOfiSinF.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}


function repInfOfiSinFGru(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selGruInfOfiSinF.add(option,selGruInfOfiSinF[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["COD_GRUPO"]);
                option.text=datos[x]["DESC_GRUPO"];
                selGruInfOfiSinF.add(option,selGruInfOfiSinF[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datInfOfiSinF.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selGru");
}


function validaCampos(){
    if(selProyInfOfiSinF.selectedIndex==0){
		selProyInfOfiSinF.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpPerInfOfiSinF.value==""){
		inpPerInfOfiSinF.focus();
        swal("Error!", "Debe indicar un periodo inicial", "error");
        return false;
    }

    if(inpPerFinInfOfiSinF.value==""){
        inpPerFinInfOfiSinF.focus();
        swal("Error!", "Debe indicar un periodo final", "error");
        return false;
    }
	
    return true;
}




function genRepInfOfiSinFProDat(){
    var datos=$("#genRepInfOfiSinForm").serializeArray();

    $.ajax
    ({
        url : '../reportes/reporte.datInfOfiSinF.php',
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
                        	genRepInfOfiSinFProDat();
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
    xmlhttp.open("POST", "../datos/datos.datInfOfiSinF.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}