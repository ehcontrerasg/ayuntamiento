/**
 * Created by Jesus Gutierrez on 02/08/2016.
 */
var selProyRecGraCli;
var inpFecIniRecGraCli;
var inpFecFinRecGraCli;
var butGenRecGraCli;
var genRepRecGraCliForm;
var inpHidValRes;
const slcUso = $("#slcUso");


function repRecGraCliInicio(){
	selProyRecGraCli=document.getElementById("selProyRecGraCli");
	inpFecIniRecGraCli=document.getElementById("inpFecIniRecGraCli");
	inpFecFinRecGraCli=document.getElementById("inpFecFinRecGraCli");
	butGenRecGraCli=document.getElementById("butGenRecGraCli");
	genRepRecGraCliForm=document.getElementById("genRepRecGraCliForm");
	butGenRecGraCli.addEventListener("click",verificaSession);
	repRecGraCliPro();
    getUsos();
    $("#genRepRecGraCliForm").submit(
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
                        compSession(genRepRecGraCliForm);
                    }
                });
        }


    )

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
    xmlhttp.open("POST", "../datos/datos.datRecGraCli.php", true);   // async
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
    var datos=$("#genRepRecGraCliForm").serializeArray();

    $.ajax
    ({
        url : '../reportes/reporte.datRecGraCli.php',
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
                        closeOnConfirm: true,
                        showLoaderOnConfirm: true
                    });

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
                        text: "La descarga del archivo puede tomar algunos minutos.\n¿Desea descargarlo ahora?",
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
    xmlhttp.open("POST", "../datos/datos.datRecGraCli.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function compSession(callback){
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

function getUsos(){
    let options = {url:"../datos/datos.datRecGraCli.php", type:"POST", data:{tip:"getUsos"}, dataType:"json"};
    $.ajax(options).done((usos) => {

        usos.forEach((uso)=>{
            slcUso.append(new Option(uso.descripcion,uso.codigo));
        });

    }).fail((error) => {
        console.error(error);
    });
}