/**
 * Created by PC on 7/7/2016.
 */
var impFacSelPro;
var impFacSelGru;
var impFacInpPer;
var impFacButCon;
var genImpFacForm;
var divRepImpFac;
var impFacInpZon;



function RefZon(){
    $(function() {
        $("#impFacInpZon").autocomplete({
            source: "../datos/datos.imprimefac2.php?proyecto="+impFacSelPro.value+"&tip=autComZon",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
                $(".ui-autocomplete").css("z-index", 1000);
            }
        });

    });

}

function impFacInicio(){

    impFacSelPro    = document.getElementById("impFacSelPro");
    impFacSelGru    = document.getElementById("impFacSelGru");
    impFacInpPer    = document.getElementById("impFacInpPer");
    impFacButCon    = document.getElementById("impFacButCon");
	genImpFacForm   = document.getElementById("genImpFacForm");
	divRepImpFac    = document.getElementById("divRepImpFac");
    impFacInpZon    = document.getElementById("impFacInpZon");
    impFacInpZon.addEventListener("keyup",RefZon);
	genImpFacForm.addEventListener("submit",genImpFacForm );
    impFacButCon.addEventListener("click",verificaSession);
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
                                text: "Desea generar las facturas del grupo "+impFacSelGru.value+" para el periodo de "+impFacInpPer.value,
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Si!",
                                cancelButtonText: "No!",
                                showLoaderOnConfirm: true,
                                closeOnConfirm: true,
                                closeOnCancel: true },
                            function(isConfirm){
                                if (isConfirm) {
                                    repImpFacGraCli();
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
        xmlhttp.open("POST", "../datos/datos.imprimefac2.php", true);   // async
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("tip=sess");

}

function impFacLleSelPro(){
    $.ajax
    ({
        url : '../datos/datos.imprimefac2.php',
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
    xmlhttp.open("POST", "../datos/datos.imprimefac2.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selGru");

}

function validaCampos(){
    if(impFacSelPro.selectedIndex==0){
		impFacSelPro.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    if(impFacSelGru.selectedIndex==0 && impFacInpZon.value.trim()==""){
		impFacSelGru.focus();
        swal("Error!", "Debe seleccionar un grupo o zona", "error");
        return false;
    }
    if(impFacInpPer.value.trim()==""){
		impFacInpPer.focus();
        swal("Error!", "Debe indicar el periodo", "error");
        return false;
    }

    return true;
}

function repImpFacGraCli(){
   // if(validaCampos()) {
        var Proyecto = impFacSelPro.value;
        var Grupo    = impFacSelGru.value;
        var Periodo  = impFacInpPer.value;
        var Zona     = impFacInpZon.value;
        divRepImpFac.data = '../reportes/reporte.imprimeFacturas2.php?proyecto=' + Proyecto + '&grupo=' + Grupo +'&periodo=' + Periodo+'&zona=' + Zona;
    //}
}