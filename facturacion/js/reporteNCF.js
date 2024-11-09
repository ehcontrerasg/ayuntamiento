/**
 * Created by Jesus on 16/10/2019.
 */
var apeLotSelPro   ;
var apeLotInpProDes;
var apeLotInpPer   ;
var apeLotInpPerDes;
var apeLotInpZon   ;
var apeLotInpZonDes;
var apeLotInpB01;
var apeLotInpB02;
var apeLotInpB14;
var apeLotInpB15;
var apeLotInpB01Tot;
var apeLotInpB02Tot;
var apeLotInpB14Tot;
var apeLotInpB15Tot;

function apeLotInicio(){
    apeLotSelPro    = document.getElementById("apeLotSelPro");
    apeLotInpProDes = document.getElementById("apeLotInpProDsc");
    apeLotInpPer    = document.getElementById("apeLotInpPer");
    apeLotInpPerDes = document.getElementById("apeLotInpPerDesc");
    apeLotInpZon    = document.getElementById("apeLotInpZon");
    apeLotInpZonDes = document.getElementById("apeLotInpZonDesc");
    apeLotInpB01 = document.getElementById("apeLotInpB01");
    apeLotInpB01Tot = document.getElementById("apeLotInpB01Tot");
    apeLotInpB02 = document.getElementById("apeLotInpB02");
    apeLotInpB02Tot = document.getElementById("apeLotInpB02Tot");
    apeLotInpB14 = document.getElementById("apeLotInpB14");
    apeLotInpB14Tot = document.getElementById("apeLotInpB14Tot");
    apeLotInpB15 = document.getElementById("apeLotInpB15");
    apeLotInpB15Tot = document.getElementById("apeLotInpB15Tot");

    apeLotInpZon.addEventListener("keyup",RefZon);
    apeLotInpZon.addEventListener("blur",refZonDes);
    apeLotSelPro.addEventListener("change",refProDes);
    apeLotSelPro.addEventListener("change",refZonDes);
    obtieneMaxPer();
    apeLotLleSelPro();
}

function obtieneMaxPer(){
    var select= apeLotInpPer;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            //option.value="";
            //option.text="";
            //select.add(option,select[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["MES"]);
                option.text=datos[x]["MAXPER"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //xmlhttp.send("tip=maxPerNCF&zona="+apeLotInpZon.value);
    xmlhttp.send("tip=maxPerNCF");
}

function apeLotLleSelPro(){
    var select= apeLotSelPro;
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
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function RefZon(){
    $(function() {
        $("#apeLotInpZon").autocomplete({
            source: "../datos/datos.apertura.php?proyecto="+apeLotSelPro.value+"&tip=autComZon",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
                $(".ui-autocomplete").css("z-index", 1000);
            }
        });
    });
}

function refZonDes() {
    if(apeLotInpZon.value.length>2){
        apeLotInpZonDes.value="ZONA "+apeLotInpZon.value;
    }
    obtieneCantidadNCF();
    /*else{
        apeLotInpZonDes.value="";
        apeLotInpB01.value="";
        apeLotInpB02.value="";
        apeLotInpB14.value="";
        apeLotInpB15.value="";
        apeLotInpB01Tot.value="";
        apeLotInpB02Tot.value="";
        apeLotInpB14Tot.value="";
        apeLotInpB15Tot.value="";
    }      */

}
function refProDes() {
    if(apeLotSelPro.value==""){
        apeLotInpProDes.value="";
    }else if(apeLotSelPro.value=="BC"){
        apeLotInpProDes.value="Boca Chica";
    }else if(apeLotSelPro.value=="SD"){
        apeLotInpProDes.value="Santo Domingo";
    }
    if(apeLotInpPer.value != ""){
        apeLotInpPerDes.value=apeLotInpPer.value;
    }
}

function obtieneCantidadNCF(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datosa= JSON.parse(xmlhttp.responseText);
            apeLotInpB01.value=datosa[0]["ID_NCF"];
            apeLotInpB01Tot.value=datosa[0]["CANTIDAD"];
            apeLotInpB02.value=datosa[1]["ID_NCF"];
            apeLotInpB02Tot.value=datosa[1]["CANTIDAD"];
            apeLotInpB14.value=datosa[2]["ID_NCF"];
            apeLotInpB14Tot.value=datosa[2]["CANTIDAD"];
            apeLotInpB15.value=datosa[3]["ID_NCF"];
            apeLotInpB15Tot.value=datosa[3]["CANTIDAD"];
        }
    }
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=obtCantNcf&zona="+apeLotInpZon.value+"&proyecto="+apeLotSelPro.value);
}

function validaCampos(){
    if(apeLotSelPro.selectedIndex==0){
        swal("Error!", "debe seleccionar el proyecto", "error");
        return false;
    }
    /*if(apeLotInpZon.value.trim()==""){
        swal("Error!", "La zona no puede ser vacia", "error");
        return false;
    }*/
    if(apeLotInpPer.value.trim()==""){
        swal("Error!", "Error en el periodo", "error");
        return false;
    }

    return true;
}