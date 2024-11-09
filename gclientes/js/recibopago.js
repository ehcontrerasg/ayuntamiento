var recPSelPro;
var recPSelGru;
var genrecPForm;
var divReprecP;
const recPTxtInm = $("#recPTxtInm");
const recPTxtRnc = $("#recPTxtRnc");
const recPTxtPeriodoInicial = $("#recPTxtPeriodoInicial");
const recPTxtPeriodoFinal = $("#recPTxtPeriodoFinal");

function recPInicio(){

    recPSelPro    = document.getElementById("recPSelPro");
    recPSelGru    = document.getElementById("recPSelGru");
    recPButCon    = document.getElementById("recPButCon");
    genrecPForm   = document.getElementById("genrecPForm");
    divReprecP    = document.getElementById("divReprecP");
    genrecPForm.addEventListener("submit",genrecPForm );
    recPButCon.addEventListener("click",verificaSession);
    divReprecP.addEventListener('load',function(){
        swal("Mensaje!", "Recibo de pago generados exitosamente!", "success");
    });
    recPLleSelPro();
    recPLleSelGru();
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
                                text: "Desea generar los recibo de pago del grupo "+recPSelGru.value+" para el periodo de "+recPTxtPeriodoInicial.val() + " - "+ recPTxtPeriodoFinal.val() ,
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
        xmlhttp.open("POST", "../datos/datos.reciboPagosgc.php", true);   // async
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("tip=sess");

}

function recPLleSelPro(){
    $.ajax
    ({
        url : '../datos/datos.reciboPagosgc.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#recPSelPro').empty();
            $('#recPSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {

                $('#recPSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {


        }
    });

}

function recPLleSelGru(){
    var select= recPSelGru;
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
    xmlhttp.open("POST", "../datos/datos.reciboPagosgc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selGru");

}

function validaCampos(){
    if(recPSelPro.selectedIndex==0){
       recPSelPro.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    /*if(recPSelGru.selectedIndex==0){
        recPSelGru.focus();
        swal("Error!", "Debe seleccionar un grupo o zona", "error");
        return false;
    }
    if(impFacInpPer.value.trim()==""){
        impFacInpPer.focus();
        swal("Error!", "Debe indicar el periodo", "error");
        return false;
    }*/

    return true;
}

 function repImpFacGraCli(){
   var proyecto = recPSelPro.value;
   var grupo = recPSelGru.value;
   var inmueble = recPTxtInm.val();
   var rnc = recPTxtRnc.val();
   var periodoInicial = recPTxtPeriodoInicial.val();
   var periodoFinal = recPTxtPeriodoFinal.val();

   console.log('Antes de mostrar pdf: ' + divReprecP.data);
   divReprecP.data = `../reportes/reporte.reciboPagos.php?proyecto=${proyecto}&grupo=${grupo}&inmueble=${inmueble}&rnc=${rnc}&periodo_inicial=${periodoInicial}&periodo_final=${periodoFinal}`;
   console.log('Después de mostrar pdf: ' + divReprecP.data);
}

