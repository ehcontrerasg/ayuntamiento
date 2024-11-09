/**
 * Created by Jesus Gutierrez on 19/09/2016.
 */

var selAcueducto;
var inpZonini;
var inpZonfin;
var inpSecini;
var inpSecfin;
var inpRutini;
var inpRutfin;
var inpOfiini;
var inpOfifin;
var inpRecini;
var inpRecfin;
var selMotivo;
var radProcedente;
var radNoprocedente;
var radTodosres;
var radPendientes;
var radRealizadas;
var radTodosest;
var inpFecinirad;
var inpFecfinrad;
var inpFecinires;
var inpFecfinres;
var butGenResPqr;
var butExlResPqr;
var butPdfResPqr;
var radTipoResolProc;
var radTipoResolInproc;
var radTipoResolTodos;
var tipo_resol;
var radTipoEstPend;
var radTipoEstReal;
var radTipoEstTodo;
var tipo_estado;

function repResPqrInicio(){
	
	selAcueducto=document.getElementById("selAcueducto");
	inpZonini=document.getElementById("inpZonini");
	inpZonfin=document.getElementById("inpZonfin");
	inpSecini=document.getElementById("inpSecini");
	inpSecfin=document.getElementById("inpSecfin");
	inpRutini=document.getElementById("inpRutini");
	inpRutfin=document.getElementById("inpRutfin");
	inpOfiini=document.getElementById("inpOfiini");
	inpOfifin=document.getElementById("inpOfifin");
	inpRecini=document.getElementById("inpRecini");
	inpRecfin=document.getElementById("inpRecfin");
	selMotivo=document.getElementById("selMotivo");
	radProcedente=document.getElementById("radProcedente");
	radNoprocedente=document.getElementById("radNoprocedente");
	radTodosres=document.getElementById("radTodosres");
	radPendientes=document.getElementById("radPendientes");
	radRealizadas=document.getElementById("radRealizadas");
	radTodosest=document.getElementById("radTodosest");
	inpFecinirad=document.getElementById("inpFecinirad");
	inpFecfinrad=document.getElementById("inpFecfinrad");
	inpFecinires=document.getElementById("inpFecinires");
	inpFecfinres=document.getElementById("inpFecfinres");
	butGenResPqr=document.getElementById("butGenResPqr");
	butExlResPqr=document.getElementById("butExlResPqr");
	//butPdfResPqr=document.getElementById("butPdfResPqr");
	radTipoResolProc=document.getElementById("radProcedente");
	radTipoResolInproc=document.getElementById("radNoprocedente");
	radTipoResolTodos=document.getElementById("radTodosres");
	radTipoEstPend=document.getElementById("radPendientes");
	radTipoEstReal=document.getElementById("radRealizadas");
	radTipoEstTodo=document.getElementById("radTodosest");
	genResPqrForm=document.getElementById("genResPqrForm");
	inpZonini.addEventListener("keyup",RefZon);
	inpZonfin.addEventListener("keyup",RefZon);
	butGenResPqr.addEventListener("click",verificaSessionPan);
	butExlResPqr.addEventListener("click",genResPqr);
	//butPdfResPqr.addEventListener("click",verificaSessionPdf);
	repResPqrSelAcu();
	repResPqrSelMot();
	/*genResPqrForm.addEventListener("submit",genResPqr );*/
}

function genResPqr() {
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
        function (isConfirm) {
            if (isConfirm) {
                //compSession(genRepDeuGraCliDat);
                compSession(genRepResPQR);
                /*genRepResPQR();*/
				//alert("clicked");
            }
        });
}

function genRepResPQR(){
    var datos=$("#genResPqrForm").serializeArray();

    $.ajax
    ({
        url : '../reportes/reporte.resumen_pqr.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){
				console.log(urlPdf);
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
            swal("Error!", "Error desconocido contacte a sistemas", "error");
        }
    });
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
                $("#ingResInsInpCodSis").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password' tabindex='4' placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },

                    function(inputValue){
                        if (inputValue === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{
                            $("#ingResInsInpCodSis").focus();
                            iniSes();
                        }
                    }
                );


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
function RefZon(){
    $(function() {
        $("#inpZonini").autocomplete({
            source: "../datos/datos.repo_resumen_pqr.php?proyecto="+selAcueducto.value+"&tip=autComZon",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
                $(".ui-autocomplete").css("z-index", 1000);
            }
        });

    });
	$(function() {
        $("#inpZonfin").autocomplete({
            source: "../datos/datos.repo_resumen_pqr.php?proyecto="+selAcueducto.value+"&tip=autComZon",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
                $(".ui-autocomplete").css("z-index", 1000);
            }
        });

    });
}

function repResPqrSelAcu(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selAcueducto.add(option,selAcueducto[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
				selAcueducto.add(option,selAcueducto[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.repo_resumen_pqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function repResPqrSelMot(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
			selMotivo.add(option,selMotivo[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_MOTIVO_REC"]);
                option.text=datos[x]["ID_MOTIVO_REC"]+' - '+datos[x]["DESC_MOTIVO_REC"];
				selMotivo.add(option,selMotivo[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.repo_resumen_pqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selMot");
}

function validaCampos(){
    if(selAcueducto.selectedIndex==0){
		selAcueducto.focus();
        swal("Error!", "Debe seleccionar el acueducto", "error");
        return false;
    }

   return true;
}

function verificaSessionPan(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
				if(validaCampos()){
					 swal({
                     	title: "Mensaje",
                        text: "Se mostraran los resultados en pantalla!!!!",
                        type: "info",
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
                        	genflexirespqr();
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
    xmlhttp.open("POST", "../datos/datos.repo_resumen_pqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function verificaSessionExl(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
				if(validaCampos()){
					 swal({
                     	title: "Mensaje",
                        text: "La descarga del archivo puede tomar algunos minutos. <br> Desea descargarlo ahora?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si!",
                        cancelButtonText: "No!",
                        showLoaderOnConfirm: true,
						html:true,
                        closeOnConfirm: true,
                        closeOnCancel: true 
					},
                    function(isConfirm){
                    	if (isConfirm) {
                        	genResPqrExlDat();
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
    xmlhttp.open("POST", "../datos/datos.repo_resumen_pqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function genflexirespqr(){
	
	if(radTipoResolProc.checked == true) tipo_resol = 1;
	if(radTipoResolInproc.checked == true) tipo_resol = 2;
	if(radTipoResolTodos.checked == true) tipo_resol = 3;
	
	if(radTipoEstPend.checked == true) tipo_estado = 1;
	if(radTipoEstReal.checked == true) tipo_estado = 2;
	if(radTipoEstTodo.checked == true) tipo_estado = 3;
	
	$('.flexme1').flexigrid();
	$('.flexme2').flexigrid({height:'auto',striped:false});
	$("#flexirespqr").flexigrid	({
		
		url: '../datos/datos.repo_resumen_pqr.php?tip=resPagPan&proyecto='+selAcueducto.value+
		'&zonini='+inpZonini.value+'&zonfin='+inpZonfin.value+
		'&secini='+inpSecini.value+'&secfin='+inpSecfin.value+
		'&rutini='+inpRutini.value+'&rutfin='+inpRutfin.value+
		'&ofiini='+inpOfiini.value+'&ofifin='+inpOfifin.value+
		'&recini='+inpRecini.value+'&recfin='+inpRecfin.value+
		'&motivo='+selMotivo.value+
		'&fecinirad='+inpFecinirad.value+'&fecfinrad='+inpFecfinrad.value+
		'&fecinires='+inpFecinires.value+'&fecfinres='+inpFecfinres.value+
		'&tipo_resol='+tipo_resol+'&tipo_estado='+tipo_estado,
		dataType: 'json',
		colModel : [
			{display: 'N&deg;', name: 'rnum', width: 25,  align: 'left'},
			{display: 'C&oacute;digo<br>PQR', name: 'CODIGO_PQR', width: 45, sortable: true, align: 'left'},
			{display: 'Fecha<br>Radicaci&oacute;n', name: 'FECRAD', width: 110, sortable: true, align: 'left'},
			{display: 'Inmueble', name: 'COD_INMUEBLE', width: 50, sortable: true, align: 'left'},
			{display: 'Cliente', name: 'nom_cliente', width: 180, sortable: true, align: 'left'},
			{display: 'Medio<br>Recepci&oacute;n', name: 'medio_rec_pqr', width: 60, sortable: true, align: 'left'},
			{display: 'Zona', name: 'id_zona', width: 30, sortable: true, align: 'left'},
			{display: 'Gerencia', name: 'gerencia', width: 45, sortable: true, align: 'left'},
			{display: 'Oficina', name: 'cod_viejo', width: 45, sortable: true, align: 'left'},
			{display: 'Descripci&oacute;n', name: 'descripcion', width: 200, sortable: true, align: 'left'},
			{display: 'Fecha<br>Diagnostico', name: 'fecdiag', width: 70, sortable: true, align: 'left'},
			{display: 'Diagnostico', name: 'diagnostico', width: 100, sortable: true, align: 'left'},
			{display: 'Fecha<br>Resol.', name: 'fecresol', width: 70, sortable: true, align: 'left'},
			{display: 'Tipo', name: 'desc_motivo_rec', width: 170, sortable: true, align: 'left'},
			{display: 'Resolucion', name: 'resolucion', width: 100, sortable: true, align: 'left'},
			{display: 'Descripci&oacute;n<br>Resoluci&oacute;n', name: 'respuesta', width: 200, sortable: true, align: 'left'}
		],	
		//sortname: "I.ID_PROCESO",
		//sortorder: "DESC",
		usepager: true,
		title: 'Listado Resumen PQRs',
		useRp: true,
		rp: 30,
		page: 1,			
		width: 1120,
		height: 170
	});
	$("#flexirespqr").flexOptions({url: './../datos/datos.repo_resumen_pqr.php?tip=resPagPan&proyecto='+selAcueducto.value+
		'&zonini='+inpZonini.value+'&zonfin='+inpZonfin.value+
		'&secini='+inpSecini.value+'&secfin='+inpSecfin.value+
		'&rutini='+inpRutini.value+'&rutfin='+inpRutfin.value+
		'&ofiini='+inpOfiini.value+'&ofifin='+inpOfifin.value+
		'&recini='+inpRecini.value+'&recfin='+inpRecfin.value+
		'&motivo='+selMotivo.value+
		'&fecinirad='+inpFecinirad.value+'&fecfinrad='+inpFecfinrad.value+
		'&fecinires='+inpFecinires.value+'&fecfinres='+inpFecfinres.value+
		'&tipo_resol='+tipo_resol+'&tipo_estado='+tipo_estado,});
	$("#flexirespqr").flexReload();
}

function genResPqrExlDat(){
    var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "../datos/datos.repo_resumen_pqr.php");
	var campo1 = document.createElement("input");
	campo1.setAttribute("type", "hidden");
	campo1.setAttribute("name", "tip");
	campo1.setAttribute("value", "ResPqrExl");
	form.appendChild(campo1);
	var campo2 = document.createElement("input");
	campo2.setAttribute("type", "hidden");
	campo2.setAttribute("name", "proyecto");
	campo2.setAttribute("value", selAcueducto.value);
	form.appendChild(campo2);
	var campo3 = document.createElement("input");
	campo3.setAttribute("type", "hidden");
	campo3.setAttribute("name", "zonini");
	campo3.setAttribute("value", inpZonini.value);
	form.appendChild(campo3);
	var campo4 = document.createElement("input");
	campo4.setAttribute("type", "hidden");
	campo4.setAttribute("name", "zonfin");
	campo4.setAttribute("value", inpZonfin.value);
	form.appendChild(campo4);
	var campo5 = document.createElement("input");
	campo5.setAttribute("type", "hidden");
	campo5.setAttribute("name", "secini");
	campo5.setAttribute("value", inpSecini.value);
	form.appendChild(campo5);
	var campo6 = document.createElement("input");
	campo6.setAttribute("type", "hidden");
	campo6.setAttribute("name", "secfin");
	campo6.setAttribute("value", inpSecfin.value);
	form.appendChild(campo6);
	var campo7 = document.createElement("input");
	campo7.setAttribute("type", "hidden");
	campo7.setAttribute("name", "rutini");
	campo7.setAttribute("value", inpRutini.value);
	form.appendChild(campo7);
	var campo8 = document.createElement("input");
	campo8.setAttribute("type", "hidden");
	campo8.setAttribute("name", "rutfin");
	campo8.setAttribute("value", inpRutfin.value);
	form.appendChild(campo8);
	var campo9 = document.createElement("input");
	campo9.setAttribute("type", "hidden");
	campo9.setAttribute("name", "ofiini");
	campo9.setAttribute("value", inpOfiini.value);
	form.appendChild(campo9);
	var campo10 = document.createElement("input");
	campo10.setAttribute("type", "hidden");
	campo10.setAttribute("name", "ofifin");
	campo10.setAttribute("value", inpOfifin.value);
	form.appendChild(campo10);
	var campo11 = document.createElement("input");
	campo11.setAttribute("type", "hidden");
	campo11.setAttribute("name", "recini");
	campo11.setAttribute("value", inpRecini.value);
	form.appendChild(campo11);
	var campo12 = document.createElement("input");
	campo12.setAttribute("type", "hidden");
	campo12.setAttribute("name", "recfin");
	campo12.setAttribute("value", inpRecfin.value);
	form.appendChild(campo12);
	var campo13 = document.createElement("input");
	campo13.setAttribute("type", "hidden");
	campo13.setAttribute("name", "motivo");
	campo13.setAttribute("value", selMotivo.value);
	form.appendChild(campo13);
	var campo14 = document.createElement("input");
	campo14.setAttribute("type", "hidden");
	campo14.setAttribute("name", "fecinirad");
	campo14.setAttribute("value", inpFecinirad.value);
	form.appendChild(campo14);
	var campo15 = document.createElement("input");
	campo15.setAttribute("type", "hidden");
	campo15.setAttribute("name", "fecfinrad");
	campo15.setAttribute("value", inpFecfinrad.value);
	form.appendChild(campo15);
	var campo16 = document.createElement("input");
	campo16.setAttribute("type", "hidden");
	campo16.setAttribute("name", "fecinires");
	campo16.setAttribute("value", inpFecinires.value);
	form.appendChild(campo16);
	var campo17 = document.createElement("input");
	campo17.setAttribute("type", "hidden");
	campo17.setAttribute("name", "fecfinres");
	campo17.setAttribute("value", inpFecfinres.value);
	form.appendChild(campo17);
	var campo18 = document.createElement("input");
	campo18.setAttribute("type", "hidden");
	campo18.setAttribute("name", "tipo_resol");
	campo18.setAttribute("value", tipo_resol);
	form.appendChild(campo18);
	var campo19 = document.createElement("input");
	campo19.setAttribute("type", "hidden");
	campo19.setAttribute("name", "tipo_estado");
	campo19.setAttribute("value", tipo_estado);
	form.appendChild(campo19);
	document.body.appendChild(form);
	form.submit(); 
}


