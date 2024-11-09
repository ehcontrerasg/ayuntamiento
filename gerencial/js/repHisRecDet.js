/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyHist;
var inpPerInini;
var inpPerFin;
var genRepHistForm;
var butGenRep
var inpHidValRes;

function repDetRecInicio(){
	selProyHist=document.getElementById("selProyHist");
	inpPerInini=document.getElementById("inpPerIniHist");
	inpPerFin=document.getElementById("inpPerFinHist");
	butGenRep=document.getElementById("butGenHist");
	genRepHistForm=document.getElementById("genRepHisRecDetForm");
    butGenRep.addEventListener("click",verificaSession);
    repHistSelPro();

}

function repHistSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selProyHist.add(option,selProyHist[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                selProyHist.add(option,selProyHist[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepHisRecDet.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}


function validaCampos(){
    if(selProyHist.selectedIndex==0){
        selProyHist.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpPerInini.value==""){
		inpPerInini.focus();
        swal("Error!", "Debe indicar un periodo inicial", "error");
        return false;
    }


    if(inpPerFin.value==""){
        inpPerFin.focus();
        swal("Error!", "Debe indicar un periodo final", "error");
        return false;
    }
	
    return true;
}

function genRepHistFac(){

    var datos=$("#genRepHisRecDetForm").serializeArray();
    datos.push({name:'tip',value:'repHist'});
    $.ajax
    ({
        url : '../datos/datos.datRepHisRecDet.php',
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
                            genRepHistFac();
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
    xmlhttp.open("POST", "../datos/datos.datRepHisRecDet.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}