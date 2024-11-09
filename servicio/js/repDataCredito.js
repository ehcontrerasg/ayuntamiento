/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */

var selProyDataCredito;
var inpFecIniDataCredito;
var inpFecFinDataCredito;
var butGenDataCredito;
var genRepDataCreditoForm;
var inpHidValRes;

function repDataCreditoInicio(){
	selProyDataCredito=document.getElementById("selProyDataCredito");
	inpFecIniDataCredito=document.getElementById("inpFecIniDataCredito");
	inpFecFinDataCredito=document.getElementById("inpFecFinDataCredito");
	inpHidValRes=document.getElementById("inpHidValRes");
	butGenDataCredito=document.getElementById("butGenDataCredito");
	genRepDataCreditoForm=document.getElementById("genRepDataCreditoForm");
	butGenDataCredito.addEventListener("click",verificaSession);
	repDataCreditoSelPro();
	genRepDataCreditoForm.addEventListener("submit",genRepDataCreditoForm );
}

function repDataCreditoSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyDataCredito.add(option,selProyDataCredito[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyDataCredito.add(option,selProyDataCredito[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepDataCredito.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function validaCampos(){
    if(selProyDataCredito.selectedIndex==0){
		selProyDataCredito.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpFecIniDataCredito.value==""){
		inpFecIniDataCredito.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	
	if(inpFecFinDataCredito.value==""){
		inpFecFinDataCredito.focus();
        swal("Error!", "Debe indicar una fecha final", "error");
        return false;
    }
	
    return true;
}

function genRepDataCreditoDat(){

    var fecini = $('#inpFecIniDataCredito').val();
    var fecfin =$('#inpFecFinDataCredito').val();
    var proyecto = $('#selProyDataCredito').val();

    $.ajax
    ({
        url : '../datos/datos.datRepDataCredito.php',
        type : 'POST',
        dataType : 'html',
        data : { tip : 'DataCredito',proyecto:proyecto,fecini:fecini,fecfin:fecfin },
        success : function(html) {
            descargarTablaHTML('Inmuebles_Data_Credito '+proyecto+'.xls',html);
            swal.close();

        },
        error : function(xhr, status) {
            swal({
                    title: "Error",
                    text: "contacte a sistemas. xhr: "+xhr+" status: "+status,
                    type:'error',
                    showConfirmButton: true },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        window.close(this);
                    }
                }
            );
        }
    });

/*    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepDataCredito.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "DataCredito");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "fecini");
	campo2.setAttribute("value", inpFecIniDataCredito.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "fecfin");
	campo3.setAttribute("value", inpFecFinDataCredito.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "proyecto");
	campo4.setAttribute("value", selProyDataCredito.value);
	form.appendChild(campo4);
	document.body.appendChild(form);
	//form.submit();*/



}

//Función para descargar en formato Excel
function descargarTablaHTML(nombre_archivo,respuesta_ajax){

    $('#table').append(respuesta_ajax);
    var str = encodeURIComponent($('#table').html());
    var uri = 'data:text/csv;charset=utf-8,' + str;
    var downloadLink = document.createElement("a");
    downloadLink.href = uri;
    downloadLink.download =nombre_archivo;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
    $("#table").empty();
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
                        	genRepDataCreditoDat();
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
    xmlhttp.open("POST", "../datos/datos.datRepDataCredito.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}