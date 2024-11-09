/*
*	Created By AMOSQUEA 27/11/2017
 */

var proyecto;
var concepto;
var periodo;
var genRepDepCon;

function repPagConInicio() {

	proyecto = document.getElementById('proyecto');
	concepto = document.getElementById('concepto');
	periodo = document.getElementById('periodo');
	genRepDepCon = document.getElementById('genRepDepCon');

	genRepDepCon.addEventListener("click",repPagConLlegaGenRep);
	repPagConLlegaSelAcu();
}

function repPagConLlegaSelAcu(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="Proyecto";
            proyecto.add(option,proyecto[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                proyecto.add(option,proyecto[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.repPagConc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=acu");

}

function repPagConLlegaGenRep(){
    if(validaCampos()) {

        swal({
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
               generaRep();
           }
       });
    }
}

function generaRep(){
    $.ajax
    ({
        url : "../datos/datos.repValDepCon.php?proy="+proyecto.value+"&concepto="+concepto.value+"&periodo="+periodo.value,
        type : 'GET',
        dataType : 'text',
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

function validaCampos(){
    if(proyecto.selectedIndex==0){
        swal("Error!", "Debe seleccionar el acueducto", "error");
        return false;
    }

    if(concepto.selectedIndex==0){
        swal("Error!", "Debe seleccionar un concepto", "error");
        return false;
    }
    
    if(periodo.value == "" || periodo.value.length != 6 || isNaN(periodo.value)){
        swal("Error!", "Debe seleccionar un periodo Correcto", "error");
        return false;
    }

    return true;
}
