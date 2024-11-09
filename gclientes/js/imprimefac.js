/**
 * Created by PC on 7/7/2016.
 */
var impFacSelPro;
var impFacSelGru;
//var impFacInpPer;
var impFacButCon;
var genImpFacForm;
var divRepImpFac;
const impFacTxtRnc = $("#impFacTxtRnc");
const impFacTxtPeriodoInicial = $("#impFacTxtPeriodoInicial");
const impFacTxtPeriodoFinal = $("#impFacTxtPeriodoFinal");

function impFacInicio(){

    impFacSelPro    = document.getElementById("impFacSelPro");
    impFacSelGru    = document.getElementById("impFacSelGru");
    //impFacInpPer    = document.getElementById("impFacInpPer");
    impFacButCon    = document.getElementById("impFacButCon");
	genImpFacForm   = document.getElementById("genImpFacForm");
	divRepImpFac    = document.getElementById("divRepImpFac");
	genImpFacForm.addEventListener("submit",genImpFacForm );
    impFacButCon.addEventListener("click",verificaSession);
    divRepImpFac.addEventListener('load',function(){
        swal("Mensaje!", "Facturas generadas exitosamente!", "success");
    });
    impFacLleSelPro();
	impFacLleSelGru();
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
                                text: "Desea generar las facturas del grupo "+impFacSelGru.value+" para el periodo de "+impFacTxtPeriodoInicial.val() + " - "+ impFacTxtPeriodoFinal.val() ,
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Si!",
                                cancelButtonText: "No!",
                                showLoaderOnConfirm: true,
                                closeOnConfirm: false,
                                closeOnCancel: true },
                            function(isConfirm){
                                if (isConfirm) {
                                    repImpFacGraCli();
                                    /*swal({title: "Mensaje!",
                                        text: "Reporte generado exitosamente.",
                                        type: "success"});*/

                                }
                            });

                    }
                }else{
                    swal({
                            title: "Mensaje!",
                            text: "Su sesion ha finalizado.",
							type: "info",
                            showConfirmButton: true },
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
            }
        }
        xmlhttp.open("POST", "../datos/datos.imprimefac.php", true);   // async
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("tip=sess");

}

function impFacLleSelPro(){
    $.ajax
    ({
        url : '../datos/datos.imprimefac.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#impFacSelPro').empty();
            $('#impFacSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {

                $('#impFacSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {


        }
    });

}

function impFacLleSelGru(){
    var select= impFacSelGru;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["COD_GRUPO"]);
                option.text=datos[x]["COD_GRUPO"]+' - '+datos[x]["DESC_GRUPO"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.imprimefac.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selGru");

}

function validaCampos(){
    if(impFacSelPro.selectedIndex==0){
		impFacSelPro.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    /*if(impFacSelGru.selectedIndex==0){
		impFacSelGru.focus();
        swal("Error!", "Debe seleccionar un grupo o zona", "error");
        return false;
    }*/
    /*if(impFacInpPer.value.trim()==""){
		impFacInpPer.focus();
        swal("Error!", "Debe indicar el periodo", "error");
        return false;
    }*/

    return true;
}

 function repImpFacGraCli(){
   var proyecto = impFacSelPro.value;
   var grupo = impFacSelGru.value;
   var rnc = impFacTxtRnc.val();
   var periodoInicial = impFacTxtPeriodoInicial.val();
   var periodoFinal = impFacTxtPeriodoFinal.val();

   console.log('Antes de mostrar pdf: ' + divRepImpFac.data);
    divRepImpFac.data = `../reportes/reporte.imprimeFacturas.php?proyecto=${proyecto}&grupo=${grupo}&documento=${rnc}&periodo_inicial=${periodoInicial}&periodo_final=${periodoFinal}`;
   console.log('Después de mostrar pdf: ' + divRepImpFac.data);
   /*let opciones = {type:'get', url:`../reportes/reporte.imprimeFacturas.php?proyecto=${proyecto}&grupo=${grupo}&rnc=${rnc}&periodo_inicial=${periodoInicial}&periodo_final=${periodoFinal}`};

   $.ajax(opciones).done(res=>{
       divRepImpFac.data = res/!*`../reportes/reporte.imprimeFacturas.php?proyecto=${proyecto}&grupo=${grupo}&rnc=${rnc}&periodo_inicial=${periodoInicial}&periodo_final=${periodoFinal}`*!/;
   });*/
}

