/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyResRecEnt;
var inpFecIniResRecEnt;
var inpFecFinResRecEnt;
var butGenResRecEnt;
var genRepResRecEntForm;
var inpHidValRes;

function repResRecEntInicio(){
	selProyResRecEnt=document.getElementById("selProyResRecEnt");
	inpFecIniResRecEnt=document.getElementById("inpFecIniResRecEnt");
	inpFecFinResRecEnt=document.getElementById("inpFecFinResRecEnt");
	inpHidValRes=document.getElementById("inpHidValRes");
	butGenResRecEnt=document.getElementById("butGenResRecEnt");
	genRepResRecEntForm=document.getElementById("genRepResRecEntForm");
	butGenResRecEnt.addEventListener("click",verificaSession);
	repResRecEntSelPro();
	/*genRepHisRecForm.addEventListener("submit",genRepHisRecForm );*/
}

function repResRecEntSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyResRecEnt.add(option,selProyResRecEnt[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyResRecEnt.add(option,selProyResRecEnt[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepResRecEnt.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function validaCampos(){
    if(selProyResRecEnt.selectedIndex==0){
		selProyResRecEnt.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpFecIniResRecEnt.value==""){
		inpFecIniResRecEnt.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	
	if(inpFecFinResRecEnt.value==""){
		inpFecFinResRecEnt.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	
    return true;
}

function genRepResRecEntDat(){
    /*var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepResRecEnt.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "ResRecEnt");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "fecini");
	campo2.setAttribute("value", inpFecIniResRecEnt.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "fecfin");
	campo3.setAttribute("value", inpFecFinResRecEnt.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "proyecto");
	campo4.setAttribute("value", selProyResRecEnt.value);
	form.appendChild(campo4);
	document.body.appendChild(form);
	form.submit(); */
    var datos=$("#genRepResRecEntForm").serializeArray();
    datos.push({name:'tip',value:'ResRecEnt'});
    $.ajax
    ({
        url : '../datos/datos.datRepResRecEnt.php',
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
                        	genRepResRecEntDat();
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
    xmlhttp.open("POST", "../datos/datos.datRepResRecEnt.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}