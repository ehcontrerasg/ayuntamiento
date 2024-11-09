/**
 * Created by Jesus Gutierrez on 25/07/2016.
 */
 
$(document).ready(function(){
    //desContr();
    compSession();
    compSession(llenarSpinerPro);

    $("#genRepRelPagForm").submit(
       function(){
           swal
           ({
                   title: "Advertencia!",
                   text: "El reporte puede tardar algunos minutos en generarse.",
				   type: "info",
                   showConfirmButton: true,
				   confirmButtonText: "Continuar!",
                   showCancelButton: true,
                   showLoaderOnConfirm: true,
                   closeOnConfirm: false,
                   closeOnCancel: true
               },
               function(isConfirm)
               {
                   if (isConfirm)
                   {
                       compSession(generaRep);
                   }
               });
       }


    )

});

function generaRep(){
    var datos=$("#genRepRelPagForm").serializeArray();
    $.ajax
    ({
        url : '../reportes/reportes.Relacion_pagos.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){

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

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.datRepHisRec.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selProyRelPag').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selProyRelPag').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu",function(e){return false;});

}

function compSession(callback)
{
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data:{tip : 'sess'},
        dataType : 'json',
        success : function(json) {
            if(json==true){
                if(callback){
                    callback();
                }
            }else if(json==false){
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();
                        }
                    });
                return false;
            }
        },
        error : function(xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}



/*
var selProyRelPag;
var inpFecIniRelPag;
var inpFecFinRelPag;
var butGenRelPag;

function repRelPagInicio(){
	selProyRelPag=document.getElementById("selProyRelPag");
	inpFecIniRelPag=document.getElementById("inpFecIniRelPag");
	inpFecFinRelPag=document.getElementById("inpFecFinRelPag");
	butGenRelPag=document.getElementById("butGenRelPag");
	butGenRelPag.addEventListener("click",verificaSession);
	repRelPagSelPro();
}

function validaCampos(){
    if(selProyRelPag.selectedIndex==0){
		selProyRelPag.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpFecIniRelPag.value==""){
		inpFecIniRelPag.focus();
        swal("Error!", "La fecha inicio no puede ser vacia", "error");
        return false;
    }
	
	 if(inpFecFinRelPag.value==""){
		inpFecFinRelPag.focus();
        swal("Error!", "La fecha final no puede ser vacia", "error");
        return false;
    }
    return true;
}

function genRepRelPagDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.datRepRelPag.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "relPag");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "fecini");
	campo2.setAttribute("value", inpFecIniRelPag.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "fecfin");
	campo3.setAttribute("value", inpFecFinRelPag.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "proyecto");
	campo4.setAttribute("value", selProyRelPag.value);
	form.appendChild(campo4);
	document.body.appendChild(form);
	form.submit(); 
}

function repRelPagSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selProyRelPag.add(option,selProyRelPag[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selProyRelPag.add(option,selProyRelPag[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepRelPag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

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
                        	genRepRelPagDat();
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
                            //top.location.replace("../../index.php")
							window.close(this);
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepRelPag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}*/